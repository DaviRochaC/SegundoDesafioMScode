<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');

use App\Models\Cliente;
use App\Models\Services\Auth\Middleware;


$clienteModel = new Cliente();

Middleware::verificaCampos($_POST, array('nome', 'cpf_cnpj', 'email'), 'http://localhost/mscode/challengetwo/views/admin/cadastrarAdmin.php', 'Todos os campos são obrigatórios');


if (strlen($_POST['cpf_cnpj']) < 14 or strlen($_POST['cpf_cnpj']) > 18) {
    $_SESSION['danger'] = 'CPF ou CNPJ inválido';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/cadastrar.php');
    die();
}

if (strlen($_POST['cpf_cnpj']) == 15 or strlen($_POST['cpf_cnpj']) == 16 or strlen($_POST['cpf_cnpj']) == 17) {
    $_SESSION['danger'] = 'CPF ou CNPJ inválido';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/cadastrar.php');
    die();
}

$cpfOuCnpj = $clienteModel->formataCpfeCnpj(htmlspecialchars($_POST['cpf_cnpj']));


$emailJaCadastradoNoBanco = $clienteModel->busca('email', htmlspecialchars($_POST['email']));
$cpfOuCnpjJaCadastradoNoBanco =  $clienteModel->busca('cpf_cnpj', $cpfOuCnpj);

if ($emailJaCadastradoNoBanco) {
    $_SESSION['danger'] = 'Já existe um cliente vinculado ao email informado';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/cadastrar.php');
    die();
}

if ($cpfOuCnpjJaCadastradoNoBanco) {
    $_SESSION['danger'] = 'Já existe um cliente vinculado ao CPF ou CNPJ informado';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/cadastrar.php');
    die();
}


$arrayCliente = [
    'nome' => htmlspecialchars($_POST['nome']),
    'email' => htmlspecialchars($_POST['email']),
    'cpf_cnpj' => $cpfOuCnpj,
];


$clienteModel->create($arrayCliente);

$_SESSION['success'] = 'Cliente cadastrado com sucesso!';
header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/cadastrar.php');
die();