<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;

Middleware::verificaAdminLogado();

if (intval(base64_decode($_POST['i'])) <= 0) {
    Middleware::redirecionar('/views/admin/dashboard.php', 'danger', 'Ocorreu um erro tente novamente!');
}

Middleware::verificaCampos($_POST, array('senhaAtual', 'novaSenha', 'confirmacaoSenha'), '/views/admin/password/alterarSenha.php?i=' . $_POST['i'], 'Todos os campos são obrigátorios');


$adminModel = new Administrador();

$id = intval(base64_decode($_POST['i']));
$admin = $adminModel->busca('id', $id);

if (!$admin) {
    Middleware::redirecionar('/views/admin/dashboard.php', 'danger', 'Ocorreu um erro tente novamente!');
}

if (md5(htmlspecialchars($_POST['senhaAtual'])) != $admin['senha']) {
    Middleware::redirecionar('/views/admin/password/alterarSenha.php?i=' . $_POST['i'], 'danger', 'Senha atual incorreta!');
}


if (md5($_POST['novaSenha']) !=  md5($_POST['confirmacaoSenha'])) {
    Middleware::redirecionar('/views/admin/password/alterarSenha.php?i=' . $_POST['i'], 'danger', 'A nova senha e a sua confirmação nao conferem!');
}

$arrayAdmin = [
    'senha' => md5(htmlspecialchars($_POST['novaSenha']))
];

$adminModel->update($arrayAdmin, intval($admin['id']));

Middleware::redirecionar('/views/admin/password/alterarSenha.php?i=' . $_POST['i'], 'success', 'Senha alterada com sucesso!');
