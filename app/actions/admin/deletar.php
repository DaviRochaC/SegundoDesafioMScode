<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;

Middleware::verificaAdminLogado();
Middleware::verificaAdminMaster('/views/admin/dashboard.php');

if ((!isset($_GET['i'])) or (intval(base64_decode($_GET['i'])) <= 0)) {
    Middleware::redirecionar('/views/admin/gerenciarAdmin.php', 'danger', 'Ocorreu um erro tente novamente!');
}

$adminModel = new Administrador();

$id = intval(base64_decode($_GET['i']));

$admin = $adminModel->busca('id', $id);


$adminModel->delete(intval($admin['id']));

//TODO ENVIAR EMAIL DE DESLIGAMENTO;

Middleware::redirecionar('/views/admin/gerenciarAdmin.php', 'success', 'Administrador deletado com sucesso!');

