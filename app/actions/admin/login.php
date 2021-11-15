<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require("../../../vendor/autoload.php");

use App\Models\Services\Auth\Middleware;
use App\Models\Administrador;

Middleware::verificaCampos($_POST, array('cpf', 'password'), 'http://localhost/mscode/challengetwo/views/admin/login.php', 'CPF ou senha inválidos');

$adminModel = new Administrador();

if(strlen($_POST['cpf'])!= 14){
    $_SESSION['danger'] = 'CPF ou senha inválidos';
    header('Location: http://localhost/mscode/challengetwo/views/admin/login.php');
    die();
}

$cpf = $adminModel->limpacpf(htmlspecialchars($_POST['cpf']));


$admin = $adminModel->busca('cpf',$cpf);


if (!$admin) {
    $_SESSION['danger'] = 'CPF ou senha inválidos';
    header('Location: http://localhost/mscode/challengetwo/views/admin/login.php');
    die();
}


if ($admin['senha'] != md5(htmlspecialchars($_POST['password']))) {
    $_SESSION['danger'] = 'CPF ou senha inválidos';
    header('Location: http://localhost/mscode/challengetwo/views/admin/login.php');
    die();
}

$_SESSION['admin'] = [
    'nome' => $admin['nome'],
    'id' => $admin['id'],
    'admin_master' => $admin['admin_master']
];

header('Location:http://localhost/mscode/challengetwo/views/admin/dashboard.php');
die();
