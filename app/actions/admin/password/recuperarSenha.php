<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require('../../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;
use App\Models\Services\Communication\Email;

Middleware::verificaCampos($_POST, array('cpf'), '/views/admin/password/recuperarSenha.php', 'Para prosseguir informe seu CPF!');

$adminModel = new Administrador();
$emailModel = new Email();

$cpf = $adminModel->limpaCpf(htmlspecialchars($_POST['cpf']));

if (strlen($cpf) != 11) {
    Middleware::redirecionar('/views/admin/password/recuperarSenha.php', 'danger', 'CPF inválido!');
}

$admin = $adminModel->busca('cpf', $cpf);

if (!$admin) {
    Middleware::redirecionar('/views/admin/password/recuperarSenha.php', 'danger', 'CPF inválido!');
}

$token = Administrador::gerarToken();

$arrayAdmin = [
    'token' => $token
];


$adminModel->update($arrayAdmin, intval($admin['id']));


//refazer esse codigo  daqui pra baixo
$assunto = 'Recuperar senha - Painel Administrativo da Ghapic';

$_SESSION['recuperar_senha_admin'] = "Olá {$admin['nome']}, um pedido de redefinição de senha foi solicitado para a sua conta 
 {$admin['email']} no Painel administrativo da Graphic. Para confirmar este pedido e definir uma nova senha para sua conta, por favor, clique no link abaixo: <br><br> 
 <a href=\"http://localhost/mscode/challengetwo/views/admin/password/redefinirSenha.php?token=$token\">Redefinir senha</a>";


ob_start();
include('../../../../views/admin/emails/mensagemAdminRedefenirSenha.php');
$conteudoHtml = ob_get_clean();


$emailEnviado = $emailModel->enviarEmail($admin['email'], $assunto, $conteudoHtml);

if ($emailEnviado) {
    Middleware::redirecionar('/views/admin/password/recuperarSenha.php', 'success', 'Um link de redefinição de senha foi enviado para seu e-mail!');
}
