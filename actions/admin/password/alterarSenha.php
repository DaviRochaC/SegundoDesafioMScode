<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();


require_once('../../../models/Administrador.php');
require_once('../../../services/auth/midlleware.php');

$adminModel = new Administrador();


verificaCampos($_POST, array('senhaAtual', 'novaSenha', 'confirmacaoSenha'), 'http://localhost/mscode/challengetwo/views/admin/password/alterarSenha.php?i=' . $_POST['i'], 'Todos os campos são obrigátorios');



if (intval(base64_decode($_POST['i'])) <= 0) {
    $_SESSION['danger'] = 'Ocorreu um erro tente novamente!';
    header('Location:http://localhost/mscode/challengetwo/views/admin/dashboard.php');
    die();
}

$id = intval(base64_decode($_POST['i']));

$admin = $adminModel->busca('id', $id);


if (md5(htmlspecialchars($_POST['senhaAtual'])) != $admin['senha']) {
    $_SESSION['danger'] = 'Senha atual incorreta!';
    header('Location:http://localhost/mscode/challengetwo/views/admin/password/alterarSenha.php?i=' . $_POST['i']);
    die();
}


if (md5($_POST['novaSenha']) !=  md5($_POST['confirmacaoSenha'])) {
    $_SESSION['danger'] = 'A nova senha e a sua confirmação nao conferem!';
    header('Location:http://localhost/mscode/challengetwo/views/admin/password/alterarSenha.php?i=' . $_POST['i']);
    die();
}

$arrayAdmin = [
    'senha' => md5(htmlspecialchars($_POST['novaSenha']))
];

$adminModel->update($arrayAdmin, $admin['id']);

$_SESSION['success'] = 'senha alterada com sucesso!';
header('Location:http://localhost/mscode/challengetwo/views/admin/password/alterarSenha.php?i=' . $_POST['i']);
die();
