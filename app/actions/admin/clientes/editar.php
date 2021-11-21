<?php
//todo revisar action de editar
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');

use App\Models\Cliente;
use App\Models\Services\Auth\Middleware;


Middleware::verificaAdminLogado();

if (intval(base64_decode($_POST['i'])) <= 0) {
    Middleware::redirecionar('/views/admin/clientes/gerenciarClientes.php', 'danger', 'Ocorreu um erro tente novamente!');
}

Middleware::verificaCampos($_POST, array('nome', 'cpf_cnpj', 'email'), '/views/admin/clientes/editar.php?i=' . $_POST['i'], 'Todos os campos são obrigatórios');

$clienteModel = new Cliente();

$id = intval(base64_decode($_POST['i']));

$cliente = $clienteModel->busca('id', $id);

if (!$cliente) {
    Middleware::redirecionar('/views/admin/clientes/gerenciarClientes.php', 'danger', 'Ocorreu um erro tente novamente!');
}


$cpfOuCnpj = $clienteModel->removeMascara(htmlspecialchars($_POST['cpf_cnpj']));

if (strlen($cpfOuCnpj) != 11 and strlen($cpfOuCnpj) != 14) {
    Middleware::redirecionar('/views/admin/clientes/editar.php?i=' . $_POST['i'], 'danger', 'CPF ou CNPJ inválido');
}


$emailJaCadastradoNoBanco = $clienteModel->busca('email', htmlspecialchars($_POST['email']));
$cpfOuCnpjJaCadastradoNoBanco = $clienteModel->busca('cpf_cnpj', $cpfOuCnpj);


if ($emailJaCadastradoNoBanco and $emailJaCadastradoNoBanco['id'] != $cliente['id']) {
    Middleware::redirecionar('/views/admin/clientes/editar.php?i=' . $_POST['i'], 'danger', 'Já existe um cliente vinculado ao email informado');
}

if ($cpfOuCnpjJaCadastradoNoBanco and $cpfOuCnpjJaCadastradoNoBanco['id'] != $cliente['id']) {
    Middleware::redirecionar('/views/admin/clientes/editar.php?i=' . $_POST['i'], 'danger', 'Já existe um cliente vinculado ao CPF ou CNPJ informado');
}

$arrayCliente = [
    'nome' => htmlspecialchars($_POST['nome']),
    'email' => htmlspecialchars($_POST['email']),
    'cpf_cnpj' => $cpfOuCnpj,
];

$cliente = $clienteModel->update($arrayCliente, intval($cliente['id']));

Middleware::redirecionar('/views/admin/clientes/gerenciarClientes.php', 'success', 'Cliente editado com sucesso!');
