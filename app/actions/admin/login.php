<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require("../../../vendor/autoload.php");

use App\Models\Services\Auth\Middleware;
use App\Models\Administrador;

Middleware::verificaCampos($_POST, array('cpf', 'password'), '/views/admin/login.php', 'CPF ou senha inválidos!');

$adminModel = new Administrador();

if (strlen($_POST['cpf']) != 14) {
    Middleware::redirecionar('/views/admin/login.php','danger', 'CPF ou senha inválidos!');
}

$cpf = $adminModel->removeMascara(htmlspecialchars($_POST['cpf']));

$admin = $adminModel->busca('cpf', $cpf);


if (!$admin) {
    Middleware::redirecionar('/views/admin/login.php','danger', 'CPF ou senha inválidos!');
}


if ($admin['senha'] != md5(htmlspecialchars($_POST['password']))) {
    Middleware::redirecionar('/views/admin/login.php','danger', 'CPF ou senha inválidos!');
}

$_SESSION['admin'] = [
    'id' => $admin['id'],
    'nome' => $admin['nome'],
    'admin_master' => boolval($admin['admin_master']),
    'logado' => true
];

Middleware::redirecionar('/views/admin/dashboard.php');