<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;

Middleware::verificaAdminLogado();
Middleware::verificaAdminMaster('http://localhost/mscode/challengetwo/views/admin/dashboard.php');

if ((!isset($_GET['i'])) or (intval(base64_decode($_GET['i'])) <= 0)) {
    Middleware::redirecionar('danger', 'Ocorreu um erro tente novamente!', 'http://localhost/mscode/challengetwo/views/admin/gerenciarAdmin.php');
}

$adminModel = new Administrador();

$id = intval(base64_decode($_GET['i']));

$admin = $adminModel->busca('id', $id);


$adminModel->delete(intval($admin['id']));

Middleware::redirecionar('success', 'Administrador deletado com sucesso!', 'http://localhost/mscode/challengetwo/views/admin/gerenciarAdmin.php');
