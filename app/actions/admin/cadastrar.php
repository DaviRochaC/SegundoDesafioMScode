<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\{Auth\Middleware, Communication\Email};


Middleware::verificaAdminLogado();
Middleware::verificaAdminMaster('/views/admin/dashboard.php');
Middleware::verificaCampos($_POST, array('nome', 'cpf', 'email'), '/views/admin/cadastrarAdmin.php', 'Todos os campos são obrigatórios!');


$adminModel = new Administrador();
$emailModel = new Email();

$cpf = $adminModel->removeMascara(htmlspecialchars($_POST['cpf']));

if (strlen($cpf) != 11) {
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'danger', 'CPF inválido');
}

$emailJaCadastradoNoBanco = $adminModel->busca('email', htmlspecialchars($_POST['email']));
$cpfJaCadastradoNoBanco =  $adminModel->busca('cpf', $cpf);

if ($emailJaCadastradoNoBanco) {
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'danger', 'Já existe um administrador vinculado ao email informado');
}

if ($cpfJaCadastradoNoBanco) {
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'danger', 'Já existe um administrador vinculado ao CPF informado');
}

$senha = $adminModel->gerarSenha(8);

$arrayAdmin = [
    'nome' => htmlspecialchars($_POST['nome']),
    'email' => htmlspecialchars($_POST['email']),
    'cpf' => $cpf,
    'senha' => md5($senha)
];


$adminModel->create($arrayAdmin);
$admin = $adminModel->busca('email', $arrayAdmin['email']);



$assunto = 'Cadastro - Painel Administrativo da Graphic';
$_SESSION['cadastro_novo_admin'] = "Olá {$admin['nome']}, segue abaixo sua senha de acesso ao painel administrativo da Graphic.<br><br>
Senha = $senha .";

ob_start();
include('../../../views/admin/emails/mensagemAdminCadastrado.php');
$conteudoHtml = ob_get_clean();

$emailEnviado = $emailModel->enviarEmail($admin['email'], $assunto, $conteudoHtml);

if ($emailEnviado) {
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'success', 'Administrador cadastrado com sucesso!');
}
