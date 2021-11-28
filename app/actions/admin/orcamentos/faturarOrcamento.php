<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');


use App\Models\{Orcamento, Cliente};
use App\Models\Services\{Auth\Middleware, Communication\Email};


Middleware::verificaAdminLogado();
Middleware::verificaCampos($_GET, array('token'), '/views/admin/orcamentos/listarOrcamentosAceitos.php', 'Orçamento não encontrado!');

$orcamentoModel = new Orcamento();
$clienteModel = new Cliente();
$emailModel = new Email();

$orcamento = $orcamentoModel->busca('token', htmlspecialchars($_GET['token']));


if (!$orcamento) {
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosAceitos.php', 'danger', 'Orçamento não encontrado!');
}

$token = Orcamento::gerarToken();

$arrayOrcamento = [
    'motivo_status_orcamento' => null,
    'status_orcamento_id' => 5,
    'token' => $token
];

$orcamentoModel->update($arrayOrcamento, intval($orcamento['id']));

$orcamentoAtualizado = $orcamentoModel->busca('id', $orcamento['id']);
$cliente = $clienteModel->busca('id', $orcamentoAtualizado['clientes_id']);

$assunto = 'Orçamento Faturado- Graphic';
$_SESSION['orcamento_faturado'] = "Olá {$cliente['nome']}, informamos que seu orçamento foi faturado. Para continuação do processo, seu orçamento será encaminhado para nosso setor financeiro.";


ob_start();
include('../../../../views/admin/emails/mensagemClienteOrcamentoFaturado.php');
$conteudoHtml = ob_get_clean();

$emailEnviado = $emailModel->enviarEmail($cliente['email'], $assunto, $conteudoHtml);

if ($emailEnviado) {
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosAceitos.php', 'success', 'Orçamento faturado com sucesso!');
}
