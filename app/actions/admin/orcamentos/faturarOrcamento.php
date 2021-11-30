<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('America/Sao_Paulo'); //Alterando a hora para o padrão da zona da cidade de São Paulo.

require_once('../../../../vendor/autoload.php');


use App\Models\{Orcamento, Cliente};
use App\Models\Services\{Auth\Middleware, Communication\Email};


Middleware::verificaAdminLogado(); // Verifica se usuario administrador está logado.
Middleware::verificaCampos($_GET, array('token'), '/views/admin/orcamentos/listarOrcamentosAceitos.php', 'Orçamento não encontrado!'); // Verifica se o índices passados através da variável global $_GET  são vazios ou nulos.

//instancia das classes para utilização dos métodos.
$orcamentoModel = new Orcamento();
$clienteModel = new Cliente();
$emailModel = new Email();

$orcamento = $orcamentoModel->busca('token', htmlspecialchars($_GET['token']));   //buscando um orçamento no banco de dados que tenha o token passado pelo $_GET['token'] e armazenando o retorno em uma variável.


if (!$orcamento) { // Verifica se a variável $orcamento tem o retorno igual a falso. O que significa que não foi encontrado um orcamento pelo token passado pelo $_GET['token'].
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosAceitos.php', 'danger', 'Orçamento não encontrado!');
}

$token = Orcamento::gerarToken(); // Gera um token baseado em criptografia md5 de um identificador único.

// Montando um array para atualização dos dados do Orcamento encontrado e guardado na variável $orcamento.
$arrayOrcamento = [
    'motivo_status_orcamento' => null,
    'status_orcamento_id' => 5, // Mudando o id do status  do $orcamento para 5 (status = faturado).
    'token' => $token
];

$orcamentoModel->update($arrayOrcamento, intval($orcamento['id']));  //Atualizando os dados do Orçamento encontrado no banco de dados.

$orcamentoAtualizado = $orcamentoModel->busca('id', $orcamento['id']); // Buscando novamente o orçamento, mas agora atualizado e pelo id guardado em $orcamento. Armazenando também  o retorno da busca em outra variável.
$cliente = $clienteModel->busca('id', $orcamentoAtualizado['clientes_id']); // Buscando o cliente vinculado ao orcamento faturado através da chave de associação entre os dois(clientes_id).

$assunto = 'Orçamento Faturado- Graphic';  //Armazena a mensagem do assunto do email que vai ser enviado em uma variável.

// Cria uma sessão com uma mensagem passando as informações sobre o faturamento do orçamento.
$_SESSION['orcamento_faturado'] = "Olá {$cliente['nome']}, informamos que seu orçamento foi faturado. Para continuação do processo, seu orçamento será encaminhado para nosso setor financeiro.";

// Aqui utiliza-se do buffer de saida para armazenar a pagina que queremos mandar no email em uma variável.
ob_start(); // Inicializa o output buffer(região da memória onde os dados ficam armazenados temporariamente até que sejam despejados para a aplicação) e bloqueia qualquer saída para o navegador. Aqui o que estiver abaixo do ob_start será guardado temporiariamente no buffer.
include('../../../../views/admin/emails/mensagemClienteOrcamentoFaturado.php'); // Inclui o arquivo que contem o html que seŕa responsavel por estruturar o email que será enviado. 
$conteudoHtml = ob_get_clean(); // Obtêm o conteúdo do buffer e armazena na variável $conteudoHtml, e encerra o buffer de saida.

$emailEnviado = $emailModel->enviarEmail($cliente['email'], $assunto, $conteudoHtml); // Envia um email para o endereço de email do cliente encontrado vinculado ao orçamento armazendo em $orcamentoAtualizado. 

if ($emailEnviado) {  // Verifica se a variável $emailEnviado é igual a verdadeiro.
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosAceitos.php', 'success', 'Orçamento faturado com sucesso!'); //Redirecionando para pagina de Orcamentos Aceitos com uma mensagem (informando o sucesso da ação) armazenada em uma sessão.
}
