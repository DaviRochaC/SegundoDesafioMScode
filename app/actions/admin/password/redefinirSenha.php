<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require('../../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;

Middleware::verificaCampos($_POST, array('token'), '/views/admin/password/redefinirSenha.php', 'token inválido');
Middleware::verificaCampos($_POST, array('novaSenha', 'confirmacaoSenha'), '/views/admin/password/redefinirSenha.php?token=' . $_POST['token'], 'Todos os campos são obrigátorios');

$adminModel = new Administrador();
$admin = $adminModel->busca('token', htmlspecialchars($_POST['token']));

if (!$admin) {
    Middleware::redirecionar('/views/admin/login.php', 'danger', 'Token inválido');
}

if (md5(htmlspecialchars($_POST['novaSenha'])) !=  md5(htmlspecialchars($_POST['confirmacaoSenha']))) {
    Middleware::redirecionar('/views/admin/password/redefinirSenha.php?token=' . $_POST['token'], 'danger', 'A nova senha e sua confirmação não conferem!');
}

$arrayAdmin = [
    'token' => null,
    'senha' => md5(htmlspecialchars($_POST['novaSenha']))
];

$adminModel->update($arrayAdmin, intval($admin['id']));

Middleware::redirecionar('/views/admin/login.php', 'success', 'Senha alterada com sucesso!');
