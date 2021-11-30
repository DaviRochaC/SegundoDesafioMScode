<?php

//ligando o log de erro do php 
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require("../../../vendor/autoload.php");

use App\Models\Services\Auth\Middleware;
use App\Models\Administrador;

Middleware::verificaCampos($_POST, array('cpf', 'password'), '/views/admin/login.php', 'CPF ou senha inválidos!');

$adminModel = new Administrador();   // Instância da classe Administrador para utilização de seus metódos.

$cpf = Administrador::removeMascara(htmlspecialchars($_POST['cpf']));   // Remove a máscara do input do cpf e amarazena em uma variável.

if (strlen($cpf) != 11) {    // Verifica se o tamanho da string armazenada em $cpf é diferente de 11.
    Middleware::redirecionar('/views/admin/login.php', 'danger', 'CPF ou senha inválidos!');    // Redireciona para página de login com uma mensagem (informando o erro) armazenada em uma sessão.
}

$admin = $adminModel->busca('cpf', $cpf);   // Busca no banco de dados na tabela de administradores por alguma linha (administrador) que tenha o conteúdo da variavel $cpf, e armazena o retorno em uma variável.

if (!$admin) {   // Verifica se $admin tem o valor igual a falso.
    Middleware::redirecionar('/views/admin/login.php', 'danger', 'CPF ou senha inválidos!');   // Redireciona para página de login com uma mensagem (informando o erro) armazenada em uma sessão.
}

if ($admin['senha'] != md5(htmlspecialchars($_POST['password']))) {    // Verifica se a senha do administrador encontrado pelo cpf é diferente da criptografia do valor do input nomeado para receber a senha.
    Middleware::redirecionar('/views/admin/login.php', 'danger', 'CPF ou senha inválidos!');   // Redireciona para página de login com uma mensagem (informando o erro) armazenada em uma sessão.
}

if(!boolval($admin['ativo'])){   // Verifica se o valor boleano no indice "ativo" do administrador encontrado pelo cpf no banco de dados é igual a falso.
    Middleware::redirecionar('/views/admin/login.php', 'danger', 'CPF ou senha inválidos!');   // Redireciona para página de login com uma mensagem (informando o erro) armazenada em uma sessão.
}

//armazena em uma sessão um array com informações do administrador logado.
$_SESSION['admin'] = [
    'id' => $admin['id'],
    'nome' => $admin['nome'],
    'admin_master' => boolval($admin['admin_master']),
    'logado' => true
];

Middleware::redirecionar('/views/admin/dashboard.php');   // Redireciona para página Dashboard.
