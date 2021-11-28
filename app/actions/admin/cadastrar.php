<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

//ligando o log de erro do php 
require_once('../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\{Auth\Middleware, Communication\Email};

Middleware::verificaAdminLogado();  // Verifica se usuario administrador está logado.
Middleware::verificaAdminMaster('/views/admin/dashboard.php'); // Verifica se o administrador é do tipo master.
Middleware::verificaCampos($_POST, array('nome', 'cpf', 'email'), '/views/admin/cadastrarAdmin.php', 'Todos os campos são obrigatórios!');  // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.

// Instâncias para utilização dos metódos.
$adminModel = new Administrador();
$emailModel = new Email();

$cpf = Administrador::removeMascara(htmlspecialchars($_POST['cpf'])); // Remove a máscara do input do cpf e amarazena em uma variável.

if (strlen($cpf) != 11) {  // Verifica se depois da remoção da mascara o tamanho da string do cpf é diferente de 11.
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'danger', 'CPF inválido');  // Redireciona para a página do cadastro de administradores com uma mensagem armazenada em uma sessão. 
}

$emailJaCadastradoNoBanco = $adminModel->busca('email', htmlspecialchars($_POST['email'])); // Busca a string do input do email no banco de dados e armazena o retorno em uma variável.
$cpfJaCadastradoNoBanco =  $adminModel->busca('cpf', $cpf);  // Busca a string da váriavel $cpf no banco de dados e armazena o retorno em uma variável.

if ($emailJaCadastradoNoBanco) {  // Verifica se a variavel $emailJacadastronoBanco é verdadeira. 
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'danger', 'Já existe um administrador vinculado ao email informado'); // Redireciona para a página do cadastro de administradores com uma mensagem armazenada em uma sessão. 
}

if ($cpfJaCadastradoNoBanco) {   // Verifica se a variavel $cpfJaCadastradoNoBanco é verdadeira.
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'danger', 'Já existe um administrador vinculado ao CPF informado'); // Redireciona para a página do cadastro de administradores com uma mensagem armazenada em uma sessão. 
}

$senha = Administrador::gerarSenha(8); // Gera uma senha (retorna conjunto de strings embaralhadas).


// Monta array do administrador com os valores dos inputs que vieram do formulário.
$arrayAdmin = [
    'nome' => htmlspecialchars($_POST['nome']),
    'email' => htmlspecialchars($_POST['email']),
    'cpf' => $cpf,
    'senha' => md5($senha)
];

$adminModel->create($arrayAdmin); // Insere os valores do array no banco de dados na tabela administradores
$admin = $adminModel->busca('email', $arrayAdmin['email']); // realiza uma busca no banco de dados por uma linha (administrador) que contem a string do indice email passada no array do administrador.

$assunto = 'Cadastro - Painel Administrativo da Graphic'; // Armazena string em uma váriavel.

// Cria uma sessão com uma mensagem passando as informações para o administrador cadastrado.
$_SESSION['cadastro_novo_admin'] = "Olá {$admin['nome']}, segue abaixo sua senha de acesso ao painel administrativo da Graphic.<br><br>
Senha = $senha .";


// Aqui utiliza-se do buffer de saida para armazenar a página que queremos mandar no email em uma variavel.
ob_start();  // Inicializa o output buffer(região da memória onde os dados ficam armazenados temporariamente até que sejam despejados para a aplicação) e bloqueia qualquer saída para o navegador. Aqui o que estiver abaixo do ob_start será guardado temporiariamente no buffer
include('../../../views/admin/emails/mensagemAdminCadastrado.php');  // Inclui o arquivo passado. 
$conteudoHtml = ob_get_clean(); // Obtêm o conteúdo do buffer e armazena na variavel $conteudoHtml, e encerra o buffer de saida.

$emailEnviado = $emailModel->enviarEmail($admin['email'], $assunto, $conteudoHtml); // Envia um email para o endereço de email do administrador armazenado no banco de dados. 

if ($emailEnviado) { //Verifica se a variavel $emailEnviado é igual a verdadeiro.
    Middleware::redirecionar('/views/admin/cadastrarAdmin.php', 'success', 'Administrador cadastrado com sucesso!');  // Redireciona para a página do cadastro de administradores com uma mensagem armazenada em uma sessão. 
}
