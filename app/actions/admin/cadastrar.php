<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;
use App\Models\Services\Communication\Email;

$adminModel = new Administrador();
$emailModel = new Email();

Middleware::verificaCampos($_POST, array('nome', 'cpf', 'email'), 'http://localhost/mscode/challengetwo/views/admin/cadastrarAdmin.php', 'Todos os campos são obrigatórios');

if (strlen($_POST['cpf']) != 14) {
    $_SESSION['danger'] = 'CPF inválido';
    header('Location:http://localhost/mscode/challengetwo/views/admin/cadastrarAdmin.php');
    die();
}

$cpf = $adminModel->limpacpf(htmlspecialchars($_POST['cpf']));

$emailJaCadastradoNoBanco = $adminModel->busca('email', htmlspecialchars($_POST['email']));
$cpfJaCadastradoNoBanco =  $adminModel->busca('cpf', $cpf);

if ($emailJaCadastradoNoBanco) {
    $_SESSION['danger'] = 'Já existe um administrador vinculado ao email informado';
    header('Location:http://localhost/mscode/challengetwo/views/admin/cadastrarAdmin.php');
    die();
}

if ($cpfJaCadastradoNoBanco) {
    $_SESSION[''] = 'Já existe um administrador vinculado ao CPF informado';
    header('Location:http://localhost/mscode/challengetwo/views/admin/cadastrarAdmin.php');
    die();
}

$senha = $adminModel->gerarSenha(10);


$arrayAdmin = [
    'nome' => htmlspecialchars($_POST['nome']),
    'email' => htmlspecialchars($_POST['email']),
    'cpf' => $cpf,
    'senha' => md5($senha)
];

if (isset($_POST['admin_master'])) {
    $arrayAdmin = [
        'nome' => htmlspecialchars($_POST['nome']),
        'email' => htmlspecialchars($_POST['email']),
        'cpf' => $cpf,
        'senha' => md5($senha),
        'admin_master' => intval(htmlspecialchars($_POST['admin_master']))
    ];
}

$adminModel->create($arrayAdmin);
$admin = $adminModel->busca('email', $arrayAdmin['email']);

$assunto = 'Cadastro - Painel Administrativo da Ghapic';
$_SESSION['cadastro_novo_admin'] = 'Olá ' . $admin['nome'].', segue abaixo sua senha de acesso ao painel administrativo da Graphic.<br><br>
Senha = ' . $senha;

ob_start();
include('../../../views/admin/emails/messagemAdminCadastrado.php');
$conteudoHtml = ob_get_clean();

$emailEnviado = $emailModel->enviarEmail($admin['email'], $assunto, $conteudoHtml);

if ($emailEnviado) {
    $_SESSION['success'] = 'Administrador cadastrado com sucesso!';
    header('Location:http://localhost/mscode/challengetwo/views/admin/cadastrarAdmin.php');
    die();
}
