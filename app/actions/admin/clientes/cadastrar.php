<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');

use App\Models\Cliente;
use App\Models\Services\Auth\Middleware;


Middleware::verificaAdminLogado();
Middleware::verificaCampos($_POST, array('nome', 'cpf_cnpj', 'email'), '/views/admin/clientes/cadastrar.php', 'Todos os campos são obrigatórios!');

$clienteModel = new Cliente();


$cpfOuCnpj = Cliente::removeMascara(htmlspecialchars($_POST['cpf_cnpj']));

if (strlen($cpfOuCnpj) != 11 AND strlen($cpfOuCnpj) != 14) {
    Middleware::redirecionar('/views/admin/clientes/cadastrar.php', 'danger', 'CPF ou CNPJ inválido');
}

$emailJaCadastradoNoBanco = $clienteModel->busca('email', htmlspecialchars($_POST['email']));
$cpfOuCnpjJaCadastradoNoBanco =  $clienteModel->busca('cpf_cnpj', $cpfOuCnpj);

if ($emailJaCadastradoNoBanco) {
    Middleware::redirecionar('/views/admin/clientes/cadastrar.php', 'danger', 'Já existe um cliente vinculado ao email informado');
}

if ($cpfOuCnpjJaCadastradoNoBanco) {
    Middleware::redirecionar('/views/admin/clientes/cadastrar.php', 'danger', 'Já existe um cliente vinculado ao CPF ou CNPJ informado');
}

$arrayCliente = [
    'nome' => htmlspecialchars($_POST['nome']),
    'email' => htmlspecialchars($_POST['email']),
    'cpf_cnpj' => $cpfOuCnpj,
];


$clienteModel->create($arrayCliente);

Middleware::redirecionar('/views/admin/clientes/cadastrar.php', 'success', 'Cliente cadastrado com sucesso!');
