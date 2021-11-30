<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('America/Sao_Paulo'); //Alterando a hora para o padrão da zona da cidade de São Paulo.

require_once('../../../../vendor/autoload.php');


use App\Models\{Orcamento, Cliente};
use App\Models\Services\{Auth\Middleware, Communication\Email};


Middleware::verificaAdminLogado();  // Verifica se usuario administrador está logado.
Middleware::verificaCampos($_GET, array('pag'), '/views/admin/dashboard.php', 'Ocorreu um erro, tente novamente!'); // Verifica se o índices passados através da variável global $_GET  são vazios ou nulos.


// Verifica por qual página solicitou a ação de cancelar o orçamento ( página de Orçamentos criados pag = 1, ou, página de Orçamento Aceitos pag = 2).
if (intval(htmlspecialchars($_GET['pag'])) === 1) { //Verifica se o valor inteiro do $GET['pag'] é identico a 1. 
    $urlRedirecionamento = '/views/admin/orcamentos/listarOrcamentosCriados.php'; // Armazenando na variável $urlRedirecionamento a URl referente a pagina dos Orçamentos criados.
} elseif (intval(htmlspecialchars($_GET['pag'])) === 2) { //Verifica se o valor inteiro do $GET['pag'] é identico a 2.
    $urlRedirecionamento = '/views/admin/orcamentos/listarOrcamentosAceitos.php'; // Armazenando na variável $urlRedirecionamento a URl referente a pagina dos Orçamentos aceitos.
} else {
    Middleware::redirecionar('/views/admin/dashboard.php', 'danger', 'Ocorreu um erro tente novamente!');  // Redirecionando para página Dashboard com uma mensagem informando um erro armazenada em uma sessão.
}

Middleware::verificaCampos($_POST, array('token'), $urlRedirecionamento, 'Orcamento nao encontrado!');  // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.
Middleware::verificaCampos($_POST, array('motivo'), $urlRedirecionamento, 'Informe o motivo do cancelamento!');  // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.

// Instâncias para utilização dos metódos.
$orcamentoModel = new Orcamento();
$clienteModel = new Cliente();
$emailModel = new Email();

$orcamento = $orcamentoModel->busca('token', htmlspecialchars($_POST['token']));  //buscando um orçamento no banco de dados que tenha o token passado pelo $_POST['token'] e armazenando o retorno em uma variável.


if (!$orcamento) { // Verifica se a variável $orcamento tem o retorno igual a falso. O que significa que não foi encontrado um orcamento pelo token passado pelo $_POST['token'].
    Middleware::redirecionar($urlRedirecionamento, 'danger', 'Orçamento não encontrado!'); // Redireciona para pagina armazenada na variável $urlRedirecionamento com uma mensagem (informando o erro) armazenada em uma sessão.
}

$token = Orcamento::gerarToken(); // Gera um token baseado em criptografia md5 de um identificador único.


// Montando um array para atualização dos dados do Orcamento encontrado e guardado na variável $orcamento.
$arrayOrcamento = [
    'motivo_status_orcamento' => htmlspecialchars($_POST['motivo']), //informando o motivo de o orçamento estar sendo cancelado.
    'status_orcamento_id' => 3,  // Mudando o id do status do $orcamento para 3 (status = cancelado)
    'token' => $token
];

 
$orcamentoModel->update($arrayOrcamento, intval($orcamento['id']));  //Atualizando os dados do Orçamento encontrado no banco de dados.

$orcamentoAtualizado = $orcamentoModel->busca('id', $orcamento['id']);  // Buscando novamente o orçamento, mas agora atualizado e pelo id guardado em $orcamento. Armazenando também  o retorno da busca em outra variável.
$cliente = $clienteModel->busca('id', $orcamentoAtualizado['clientes_id']); // Buscando o cliente vinculado ao orcamento cancelado através da chave de associação entre os dois(clientes_id).

$assunto = 'Orçamento Cancelado - Graphic'; // Armazena a mensagem do assunto do email que vai ser enviado em uma variável.

// Cria uma sessão com uma mensagem passando as informações sobre o cancelamento do orçamento.
$_SESSION['orcamento_cancelado'] = "Olá {$cliente['nome']}, informamos que seu orçamento infelizmente foi cancelado.<br>
Motivo = {$orcamentoAtualizado['motivo_status_orcamento']}";

// Aqui utiliza-se do buffer de saida para armazenar a pagina que queremos mandar no email em uma variável.
ob_start();  // Inicializa o output buffer(região da memória onde os dados ficam armazenados temporariamente até que sejam despejados para a aplicação) e bloqueia qualquer saída para o navegador. Aqui o que estiver abaixo do ob_start será guardado temporiariamente no buffer.
include('../../../../views/admin/emails/mensagemClienteOrcamentoCancelado.php'); // Inclui o arquivo que contem o html que seŕa responsavel por estruturar o email que será enviado. 
$conteudoHtml = ob_get_clean(); // Obtêm o conteúdo do buffer e armazena na variável $conteudoHtml, e encerra o buffer de saida.

$emailEnviado = $emailModel->enviarEmail($cliente['email'], $assunto, $conteudoHtml); // Envia um email para o endereço de email do cliente encontrado vinculado ao orçamento armazendo em $orcamentoAtualizado. 

if ($emailEnviado) {  // Verifica se a variável $emailEnviado é igual a verdadeiro.
    Middleware::redirecionar($urlRedirecionamento, 'success', 'Orçamento cancelado com sucesso!'); // Redireciona para pagina armazenada na variável $urlRedirecionamento com uma mensagem (informando o sucesso da ação) armazenada em uma sessão.
}
