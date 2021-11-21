<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');

use App\Models\Services\Auth\Middleware;
use App\Models\Orcamento;
use App\Models\StatusOrcamento;


Middleware::verificaAdminLogado();
Middleware::verificaCampos($_POST, array('titulo', 'valor', 'clientes_id'), '/views/admin/orcamentos/novoOrcamento.php', 'Todos os campos são obrigátorios!');
$pdfSetado = Orcamento::verificaFileSetado($_FILES['pdf']['name'], $_FILES['pdf']['size']);

$statusOrcamentoModel = new StatusOrcamento();
$orcamentoModel = new Orcamento;

if (!$pdfSetado) {
    Middleware::redirecionar('/views/admin/orcamentos/novoOrcamento.php', 'danger', 'Todos os campos são obrigátorios!');
}



$extensao = strstr($_FILES['pdf']['name'], '.pdf');

if (!$extensao) {
    Middleware::redirecionar('/views/admin/orcamentos/novoOrcamento.php', 'danger', 'Por favor, envie o arquivo na extensão .pdf');
}

$pdf = time() . '.pdf';
$_FILES['pdf']['name'] = $pdf;

if ($_FILES['pdf']['size'] > (1024 * 1024 * 5)) {
    MIddleware::redirecionar('/views/admin/orcamentos/novoOrcamento.php', 'danger', 'arquivo enviado é muito grande, envie arquivos de até 5 MB');
}

move_uploaded_file($_FILES['pdf']['tmp_name'], "/opt/lampp/htdocs/mscode/challengetwo/views/pdf/{$_FILES['pdf']['name']}");

$urlPdf = "http://localhost/mscode/challengetwo/views/pdf/{$_FILES['pdf']['name']}";

$statusOrcamento = $statusOrcamentoModel->busca('id', 1);

$valor = $orcamentoModel->removeMascara(htmlspecialchars($_POST['valor']));
$valor = doubleval($valor) / 100;

$arrayOrcamento = [
    'titulo' => htmlspecialchars($_POST['titulo']),
    'pdf_url' => $urlPdf,
    'clientes_id' => intval($_POST['clientes_id']),
    'valor_total' => $valor,
    'status_orcamento_id' => intval($statusOrcamento['id'])
];

$orcamentoModel->create($arrayOrcamento);

Middleware::redirecionar('/views/admin/orcamentos/novoOrcamento.php', 'success', 'Orcamento criado com sucesso!');
