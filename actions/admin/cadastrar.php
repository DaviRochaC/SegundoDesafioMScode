<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../models/Administrador.php');
require_once('../../services/auth/middleware.php');




$adminModel = new Administrador();

Middleware::verificaCampos($_POST, array('nome', 'cpf', 'email'), 'http://localhost/mscode/challengetwo/views/admin/cadastrarAdmin.php', 'Todos os campos são obrigatórios');

if (strlen($_POST['cpf']) != 14) {
    $_SESSION['danger'] = 'CPF inválido';
    header('Location:http://localhost/mscode/challengetwo/views/admin/cadastrarAdmin.php');
    die();
}

$cpf = $adminModel->formatacpf(htmlspecialchars($_POST['cpf']));

var_dump($cpf);
die();
