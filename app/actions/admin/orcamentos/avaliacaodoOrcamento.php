<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');

use App\Models\{Orcamento, StatusOrcamento,Cliente};
use App\Models\Services\Auth\Middleware;

Middleware::verificaCampos($_GET, array('status', 'token'), '/views/admin/403.php');

$statusOrcamentoModel = new StatusOrcamento();
$orcamentoModel = new Orcamento();
$clienteModel = new Cliente();


$statusOrcamento = $statusOrcamentoModel->busca('id', base64_decode(htmlspecialchars($_GET['status'])));
$orcamento = $orcamentoModel->busca('token', htmlspecialchars($_GET['token']));
$motivo = null;

if (!$statusOrcamento or !$orcamento) {
    Middleware::redirecionar('/views/admin/403.php');
}


if (intval($statusOrcamento['id']) === 4) {
    Middleware::verificaCampos($_POST, array('motivo'), '/views/admin/orcamentos/avaliacaoOrcamento.php?token=' . htmlspecialchars($_GET['token']), 'Por favor informe o motivo da rejeição do orçamento');
    $motivo = htmlspecialchars($_POST['motivo']);
}


//todo token receber nulo e fazer uma pagina para cada uma das opcoes rejeitar ou aceitar

$token = $orcamentoModel->gerarToken();

$arrayOrcamento = [
    'token' => $token,
    'status_orcamento_id' => $statusOrcamento['id'],
    'motivo_status_orcamento' => $motivo
];

$orcamentoModel->update($arrayOrcamento,$orcamento['id']);

$orcamentoAtualizado = $orcamentoModel->busca('id',$orcamento['id']);
$cliente = $clienteModel->busca('id',$orcamentoAtualizado['clientes_id']);
if(intval($orcamentoAtualizado['status_orcamento_id'])=== 4){
    Middleware::redirecionar('/views/admin/orcamentos/orcamentoRecusado.php?token='.$orcamentoAtualizado['token'],);
} 
if(intval($orcamentoAtualizado['status_orcamento_id'])=== 2 ){
    Middleware::redirecionar('/views/admin/orcamentos/orcamentoAceito.php?token='.$orcamentoAtualizado['token'],);
} 


