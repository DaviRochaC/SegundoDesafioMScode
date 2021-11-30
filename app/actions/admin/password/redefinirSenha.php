<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require('../../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;
 
Middleware::verificaCampos($_POST, array('token'), '/views/admin/password/redefinirSenha.php', 'token inválido');     // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.
Middleware::verificaCampos($_POST, array('novaSenha', 'confirmacaoSenha'), '/views/admin/password/redefinirSenha.php?token=' . $_POST['token'], 'Todos os campos são obrigátorios');    // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.

$adminModel = new Administrador();    // Instância da classe Administrador para utilização de seus metódos.
$admin = $adminModel->busca('token', htmlspecialchars($_POST['token']));  // Busca no banco de dados na tabela de administradores por alguma linha (administrador) que tenha o token passado pelo input escondido do formulario via $_POST.

if (!$admin) {   // Verifica se o retorno da variável $admin é falso. O que representa que não foi encontrado nenhum administrador através do token passado pelo $_POST.
    Middleware::redirecionar('/views/admin/login.php', 'danger', 'Token inválido');    // Redireciona para a página de login com uma mensagem (informando o erro) armazenada em uma sessão.
}

if (md5(htmlspecialchars($_POST['novaSenha'])) !=  md5(htmlspecialchars($_POST['confirmacaoSenha']))) {    // Verifica se a criptografia em md5 dos dois inputs(nova senha e sua confirmação) são diferentes.
    Middleware::redirecionar('/views/admin/password/redefinirSenha.php?token=' . $_POST['token'], 'danger', 'A nova senha e sua confirmação não conferem!');   // Redireciona para a página de redefinição de senha com uma mensagem (informando o erro) armazenada em uma sessão.
}

// Monta o array com a nova senha do administrador e um novo token.
$arrayAdmin = [
    'token' => null,
    'senha' => md5(htmlspecialchars($_POST['novaSenha']))
];

$adminModel->update($arrayAdmin, intval($admin['id']));     // Atualiza a senha e o novo token do administrador no banco de dados.

Middleware::redirecionar('/views/admin/login.php', 'success', 'Senha alterada com sucesso!');     // Redireciona para a página de login com uma mensagem (informando o sucesso da ação) armazenada em uma sessão.
