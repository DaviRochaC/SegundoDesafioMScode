<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;
use App\Models\Services\Communication\Email;


Middleware::verificaAdminLogado();
Middleware::verificaAdminMaster('/views/admin/dashboard.php');

Middleware::verificaCampos($_GET, array('i'), '/views/admin/gerenciarAdmin.php', 'Ocorreu um erro tente novamente!');

if (intval(base64_decode($_GET['i'])) <= 0) {
    Middleware::redirecionar('/views/admin/gerenciarAdmin.php', 'danger', 'Ocorreu um erro tente novamente!');
}

$adminModel = new Administrador();
$emailModel = new Email();

$id = intval(base64_decode($_GET['i']));

$admin = $adminModel->busca('id', $id);

$adminModel->delete(intval($admin['id']));


$assunto = 'Desligamento - Painel Administrativo da Ghapic';
$_SESSION['desligamento_admin'] = 'Olá ' . $admin['nome'] . ',infelizmente sua conta desativada e você perdeu acesso ao painel administrativo da Graphic.';

ob_start();
include('../../../views/admin/emails/mensagemAdminDesligamento.php');
$conteudoHtml = ob_get_clean();

$emailEnviado = $emailModel->enviarEmail($admin['email'], $assunto, $conteudoHtml);

if ($emailEnviado) {
    Middleware::redirecionar('/views/admin/gerenciarAdmin.php', 'success', 'Administrador deletado com sucesso!');
}
