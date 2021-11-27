<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\{Auth\Middleware, Communication\Email};

Middleware::verificaAdminLogado();  // Verifica se usuario administrador está logado.
Middleware::verificaAdminMaster('/views/admin/dashboard.php'); // Verifica se o administrador é do tipo master.
Middleware::verificaCampos($_POST, array('nome', 'cpf', 'email'), '/views/admin/cadastrarAdmin.php', 'Todos os campos são obrigatórios!');  // Verifica se o inputs vindos do formulário são vazios ou nulos.

// Instâncias para utilização dos metódos.
$adminModel = new Administrador();  
$emailModel = new Email();

$cpf = Administrador::removeMascara(htmlspecialchars($_POST['cpf'])); // Remove a máscara do input do cpf e amarazena em uma variável.

if (strlen($cpf) != 11) {  // Verifica se depois da remoção da mascara o tamanho da string do cpf é diferente de 11.
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'danger', 'CPF inválido');   // Redireciona para a pagina do cadastro de administradores
}

$emailJaCadastradoNoBanco = $adminModel->busca('email', htmlspecialchars($_POST['email'])); // Busca a string do input do email no banco de dados e armazena o retorno em uma variável.
$cpfJaCadastradoNoBanco =  $adminModel->busca('cpf', $cpf);  // Busca a string da váriavel $cpf no banco de dados e armazena o retorno em uma variável.

if ($emailJaCadastradoNoBanco) {  // Verifica se a variavel $emailJacadastronoBanco é verdadeira. 
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'danger', 'Já existe um administrador vinculado ao email informado'); // Redireciona para a pagina do cadastro de administradores
}

if ($cpfJaCadastradoNoBanco) {   // Verifica se a variavel $cpfJaCadastradoNoBanco é verdadeira.
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'danger', 'Já existe um administrador vinculado ao CPF informado'); // Redireciona para a pagina do cadastro de administradores
}

$senha = Administrador::gerarSenha(8); // Gera uma senha com strings embaralhadas.

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
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'success', 'Administrador cadastrado com sucesso!'); // Redireciona para a pagina do cadastro de administradores
}
