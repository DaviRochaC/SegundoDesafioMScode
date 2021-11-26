<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');


use App\Models\{Orcamento, Cliente};
use App\Models\Services\{Auth\Middleware, Communication\Email};


Middleware::verificaAdminLogado();
Middleware::verificaCampos($_GET, array('token'), '/views/admin/dashboard.php', 'Ocorreu um erro, tente novamente!');


$orcamentoModel = new Orcamento();
$clienteModel = new Cliente();
$emailModel = new Email();

$orcamento = $orcamentoModel->busca('token', htmlspecialchars($_GET['token']));


if (!$orcamento) {
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosCriados.php', 'danger', 'Ocorreu um erro, tente novamente!');
}

$cliente = $clienteModel->busca('id', $orcamento['clientes_id']);

$assunto = 'Orçamento aguardando resposta - Graphic';
$_SESSION['orcamento_resposta_pendente'] = "Olá {$cliente['nome']}, nós da Graphic estamos passando para relembrar que seu orçamento já foi realizado. Ainda estamos aguardando sua resposta em relação ao mesmo.
Segue abaixo um link para avaliação e visualização do seu orçamento.<br><br>
<a href=\"http://localhost/mscode/challengetwo/views/admin/orcamentos/avaliacaoOrcamento.php?token={$orcamento['token']}\">Avaliar projeto</a>";

ob_start();
include('../../../../views/admin/emails/mensagemClienteOrcamentoPendente.php');
$conteudoHtml = ob_get_clean();

$emailEnviado = $emailModel->enviarEmail($cliente['email'], $assunto, $conteudoHtml);

if ($emailEnviado) {
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosCriados.php', 'success', 'Um e-mail informando a necessidade de uma resposta sobre o orçamento foi enviado ao cliente!');
}
