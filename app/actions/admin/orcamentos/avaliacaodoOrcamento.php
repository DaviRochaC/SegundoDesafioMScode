<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('America/Sao_Paulo'); //Alterando a hora para o padrão da zona da cidade de São Paulo.

require_once('../../../../vendor/autoload.php');

use App\Models\{Orcamento, StatusOrcamento};
use App\Models\Services\Auth\Middleware;

Middleware::verificaCampos($_GET, array('status', 'token'), '/views/admin/403.php'); // Verifica se o índices passados através da variável global $_GET  são vazios ou nulos.


// Instâncias para utilização dos metódos.
$statusOrcamentoModel = new StatusOrcamento();
$orcamentoModel = new Orcamento();

//Obs o $GET['status'] contem o id criptogrado em base64 do status que o orcamento vai receber(aceito ou rejeitado). Aceito id é 2, rejeitado é o id 4
$statusOrcamento = $statusOrcamentoModel->busca('id', base64_decode(htmlspecialchars($_GET['status'])));  // buscando no banco de dados um status Orcamento pelo descriptografia em base64 do  $GET['status'] e armazenando o retorno em uma variável.
$orcamento = $orcamentoModel->busca('token', htmlspecialchars($_GET['token']));  //buscando um orçamento no banco de dados que tenha o token passado pelo $_GET['token'] e armazenando o retorno em uma variável.
$motivo = null;  // Iniciando a váriavel $motivo como null.

if (!$statusOrcamento or !$orcamento) { // Verifica se uma das variáveis $statusOrcamento  ou $orcamento tem o retorno igual a falso. O que significa que não foi encontrado um orcamento pelo token ou não foi encontrado um status orcamento com o id passado pelo $_GET.
    Middleware::redirecionar('/views/admin/403.php'); //Redireciona para a pagina de 403(Acesso negado).
}


if (intval($statusOrcamento['id']) === 4) { //Verifica se o valor inteiro do id de status orcamento encontrado é igual a 4 (que significa que o orcamento esta recebendo o status de rejeitado).
    Middleware::verificaCampos($_POST, array('motivo'), '/views/admin/orcamentos/avaliacaoOrcamento.php?token=' . htmlspecialchars($_GET['token']), 'Por favor informe o motivo da rejeição do orçamento');  // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.
    $motivo = htmlspecialchars($_POST['motivo']);  // A variável $motivo esta recebendo o input vindo do formulario 
}


$token = Orcamento::gerarToken();  // Gera um token baseado em criptografia md5 de um identificador único.


// Montando um array para atualização dos dados do Orçamento encontrado e guardado na variável $orcamento.
$arrayOrcamento = [
    'token' => $token,
    'status_orcamento_id' => $statusOrcamento['id'],
    'motivo_status_orcamento' => $motivo
];

$orcamentoModel->update($arrayOrcamento,$orcamento['id']);  //Atualizando os dados do Orçamento encontrado no banco de dados.

$orcamentoAtualizado = $orcamentoModel->busca('id',$orcamento['id']); // Buscando novamente o orçamento, mas agora atualizado e pelo id guardado em $orcamento. Armazenando também  o retorno da busca em outra variável.


if(intval($orcamentoAtualizado['status_orcamento_id'])=== 4){  //Verificando se o status orcamento id do orçamento atualizado é igual a 4 (orcamento rejeitado).
    Middleware::redirecionar('/views/admin/orcamentos/orcamentoRecusado.php?token='.$orcamentoAtualizado['token']);  // Redireciona para a página de orcamentos recusados e passando na url o token do orcamento atualizado por $_GET no indice 'token'.
} 
if(intval($orcamentoAtualizado['status_orcamento_id'])=== 2 ){ //Verificando se o status orcamento id do orçamento atualizado é igual a 2 (orcamento aceito).
    Middleware::redirecionar('/views/admin/orcamentos/orcamentoAceito.php?token='.$orcamentoAtualizado['token']);  // Redireciona para a página de orcamentos aceitos e passando na url o token do orcamento atualizado por $_GET no indice 'token'.
}  


