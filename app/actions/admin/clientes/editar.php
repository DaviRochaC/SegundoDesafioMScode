<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();


require_once('../../../../vendor/autoload.php');

use App\Models\Cliente;
use App\Models\Services\Auth\Middleware;



Middleware::verificaAdminLogado();

if (intval(base64_decode($_POST['i'])) <= 0) {
    $_SESSION['danger'] = 'Ocorreu um erro tente novamente!';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/gerenciarClientes.php');
    die();
}


Middleware::verificaCampos($_POST, array('nome', 'cpf_cnpj', 'email'), 'http://localhost/mscode/challengetwo/views/admin/clientes/editar.php?i=' . $_POST['i'], 'Todos os campos são obrigatórios');

$clienteModel = new Cliente();

$id = intval(base64_decode($_POST['i']));

$cliente = $clienteModel->busca('id', $id);

if (!$cliente) {
    $_SESSION['danger'] = 'Ocorreu um erro tente novamente!';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/gerenciarClientes.php');
    die();
}


if (strlen($_POST['cpf_cnpj']) < 14 or strlen($_POST['cpf_cnpj']) > 18) {
    $_SESSION['danger'] = 'CPF ou CNPJ inválido';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/editar.php?i=' . $_POST['i']);
    die();
}

if (strlen($_POST['cpf_cnpj']) == 15 or strlen($_POST['cpf_cnpj']) == 16 or strlen($_POST['cpf_cnpj']) == 17) {
    $_SESSION['danger'] = 'CPF ou CNPJ inválido';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/editar.php?i=' . $_POST['i']);
    die();
}


$cpfOuCnpj = $clienteModel->limpaCpfeCnpj(htmlspecialchars($_POST['cpf_cnpj']));

$emailJaCadastradoNoBanco = $clienteModel->busca('email', htmlspecialchars($_POST['email']));
$cpfOuCnpjCadastradoNoBanco = $clienteModel->busca('cpf_cnpj', $cpfOuCnpj);


if ($emailJaCadastradoNoBanco and $emailJaCadastradoNoBanco['id'] != $cliente['id']) {
    $_SESSION['danger'] = 'Já existe um cliente vinculado ao email informado';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/cadastrar.php');
    die();
}

if ($cpfOuCnpjJaCadastradoNoBanco and $cpfOuCnpjJaCadastradoNoBanco['id'] != $cliente['id']) {
    $_SESSION['danger'] = 'Já existe um cliente vinculado ao CPF ou CNPJ informado';
    header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/cadastrar.php');
    die();
}


$arrayCliente = [
    'nome' => htmlspecialchars($_POST['nome']),
    'email' => htmlspecialchars($_POST['email']),
    'cpf_cnpj' => $cpfOuCnpj,
];


$cliente = $clienteModel->update($arrayCliente, intval($cliente['id']));


$_SESSION['success'] = 'Cliente editado com sucesso!';
header('Location:http://localhost/mscode/challengetwo/views/admin/clientes/gerenciarClientes.php');
die();
