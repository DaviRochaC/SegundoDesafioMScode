<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;

Middleware::verificaAdminLogado();    // Verifica se usuario administrador está logado.

// Obs: A variável global $_POST no indice 'i' contem o id do administrador criptografado em base64.
if (intval(base64_decode($_POST['i'])) <= 0) {    // Verifica se o valor inteiro da descriptografia em base64 do GET no indice i é menor ou igual a zero.
    Middleware::redirecionar('/views/admin/dashboard.php', 'danger', 'Ocorreu um erro tente novamente!');  // Redireciona para a página dashboard com uma mensagem (informando o erro) armazenada em uma sessão.
}

Middleware::verificaCampos($_POST, array('senhaAtual', 'novaSenha', 'confirmacaoSenha'), '/views/admin/password/alterarSenha.php?i=' . $_POST['i'], 'Todos os campos são obrigátorios'); // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.

$adminModel = new Administrador();   // Instância da classe Administrador para utilização de seus metódos.

$id = intval(base64_decode($_POST['i']));    // Armazena o valor inteiro da descriptografia em base64 do $_POST no indice i em uma variável.
$admin = $adminModel->busca('id', $id);     // Busca no banco de dados na tabela de administradores por alguma linha (administrador) que tenha o conteúdo da variavel $id, e armazena o retorno em uma variável.

if (!$admin) { // Verifica se o retorno da variável $admin é falso. O que representa que não foi encontrado nenhum administrador com o id  passado pelo $_POST
    Middleware::redirecionar('/views/admin/dashboard.php', 'danger', 'Ocorreu um erro tente novamente!');    // Redireciona para a página dashboard com uma mensagem (informando o erro) armazenada em uma sessão.
}

if (md5(htmlspecialchars($_POST['senhaAtual'])) != $admin['senha']) {     //Verifica se a criptografia em md5 do input da senha atual é diferente da senha do administrador encontrado pelo id.
    Middleware::redirecionar('/views/admin/password/alterarSenha.php?i=' . $_POST['i'], 'danger', 'Senha atual incorreta!');  // Redireciona para a página de alteração de senha do administrador com uma mensagem (informando o erro) armazenada em uma sessão.
} 


if (md5($_POST['novaSenha']) !=  md5($_POST['confirmacaoSenha'])) {   // Verifica se a criptografia em md5 dos dois inputs(nova senha e sua confirmação) são diferentes.
    Middleware::redirecionar('/views/admin/password/alterarSenha.php?i=' . $_POST['i'], 'danger', 'A nova senha e a sua confirmação nao conferem!');   // Redireciona para a página de alteração de senha do administrador com uma mensagem (informando o erro) armazenada em uma sessão.
}

// Monta o array com a nova senha do administrador
$arrayAdmin = [
    'senha' => md5(htmlspecialchars($_POST['novaSenha']))
];

$adminModel->update($arrayAdmin, intval($admin['id']));    // Atualiza a senha do administrador no banco de dados.

Middleware::redirecionar('/views/admin/password/alterarSenha.php?i=' . $_POST['i'], 'success', 'Senha alterada com sucesso!');    // Redireciona para a página de alteração de senha do administrador com uma mensagem (informando o sucesso da ação) armazenada em uma sessão.
