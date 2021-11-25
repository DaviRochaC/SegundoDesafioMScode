<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();


require_once('../../../../vendor/autoload.php');


use App\Models\{Orcamento, StatusOrcamento, Cliente};
use App\Models\Services\{Auth\Middleware, Communication\Email};

Middleware::verificaAdminLogado();
Middleware::verificaCampos($_POST, array('token'), '/views/admin/orcamentos/listarOrcamentosAceitos.php', 'Orcamento nao encontrado!');
Middleware::verificaCampos($_POST, array('motivo'), '/views/admin/orcamentos/listarOrcamentosAceitos.php', 'Informe o motivo do cancelamento!');

$orcamentoModel = new Orcamento();
$statusOrcamentoModel = new StatusOrcamento();
$clienteModel = new Cliente();
$emailModel = new Email();

$orcamento = $orcamentoModel->busca('token', htmlspecialchars($_POST['token']));
$statusOrcamento = $statusOrcamentoModel->busca('id', 3);


if (!$statusOrcamento or !$orcamento) {
    die('aqui');
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosAceitos.php', 'danger', 'Ocorreu um erro, tente novamente!');
}

$token = $orcamentoModel->gerarToken();

$arrayOrcamento = [
    'motivo_status_orcamento' => htmlspecialchars($_POST['motivo']),
    'status_orcamento_id' => $statusOrcamento['id'],
    'token' => $token
];

$orcamentoModel->update($arrayOrcamento, intval($orcamento['id']));

$orcamentoAtualizado = $orcamentoModel->busca('id', $orcamento['id']);
$cliente = $clienteModel->busca('id', $orcamentoAtualizado['clientes_id']);

$assunto = 'Orçamento Cancelado - Graphic';
$_SESSION['orcamento_cancelado'] = "Olá {$cliente['nome']}, informamos que seu orçamento infelizmente foi cancelado.<br>
Motivo = {$orcamentoAtualizado['motivo_status_orcamento']}";

ob_start();
include('../../../../views/admin/emails/mensagemClienteOrcamentoCancelado.php');
$conteudoHtml = ob_get_clean();

$emailEnviado = $emailModel->enviarEmail($cliente['email'], $assunto, $conteudoHtml);

if ($emailEnviado) {
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosAceitos.php', 'success', 'Orçamento cancelado com sucesso!');
}
