<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');


use App\Models\{Orcamento,StatusOrcamento,Cliente};
use App\Models\Services\{Auth\Middleware,Communication\Email};


Middleware::verificaAdminLogado();
Middleware::verificaCampos($_POST, array('titulo', 'valor', 'clientes_id'), '/views/admin/orcamentos/novoOrcamento.php', 'Todos os campos são obrigátorios!');
$pdfSetado = Orcamento::verificaFileSetado($_FILES['pdf']['name'], $_FILES['pdf']['size']);

$statusOrcamentoModel = new StatusOrcamento();
$orcamentoModel = new Orcamento();
$clienteModel = new Cliente();
$emailModel = new Email();

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
$cliente = $clienteModel->busca('id', intval($_POST['clientes_id']));
$valor = $orcamentoModel->removeMascara(htmlspecialchars($_POST['valor']));
$valor = doubleval($valor) / 100;
$token = $orcamentoModel->gerarToken();
$arrayOrcamento = [
    'titulo' => htmlspecialchars($_POST['titulo']),
    'pdf_url' => $urlPdf,
    'clientes_id' => intval($_POST['clientes_id']),
    'valor_total' => $valor,
    'status_orcamento_id' => intval($statusOrcamento['id']),
    'token' => $token
];

$orcamentoModel->create($arrayOrcamento);

$assunto = 'Orçamento - Ghapic';
$_SESSION['novo_orcamento'] = "Olá {$cliente['nome']}, segue abaixo um link para visualização e avaliação do seu orçamento na Graphic.<br><br>
<a href=\"http://localhost/mscode/challengetwo/views/admin/orcamentos/avaliacaoOrcamento.php?token=$token\">Avaliar projeto</a>";

ob_start();
include('../../../../views/admin/emails/mensagemAvaliacaoOrcamento.php');
$conteudoHtml = ob_get_clean();

$emailEnviado = $emailModel->enviarEmail($cliente['email'], $assunto, $conteudoHtml);

if ($emailEnviado) {
    Middleware::redirecionar('/views/admin/orcamentos/novoOrcamento.php', 'success', 'Orcamento criado com sucesso!');
}
