<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require('../../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\{Auth\Middleware,Communication\Email};

Middleware::verificaCampos($_POST, array('cpf'), '/views/admin/password/recuperarSenha.php', 'Para prosseguir informe seu CPF!'); // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.

// Instâncias para utilização dos metódos.
$adminModel = new Administrador();
$emailModel = new Email();

$cpf = Administrador::removeMascara(htmlspecialchars($_POST['cpf']));   // Remove a máscara do input do cpf e amarazena em uma variável.

if (strlen($cpf) != 11) {   // Verifica se o tamanho da string armazenada em $cpf é diferente de 11.
    Middleware::redirecionar('/views/admin/password/recuperarSenha.php', 'danger', 'CPF inválido!');    // Redireciona para a página de recuperação de senha dos administradores com uma mensagem (informando o erro) armazenada em uma sessão.
}

$admin = $adminModel->busca('cpf', $cpf);   // Busca no banco de dados na tabela de administradores por alguma linha (administrador) que tenha o cpf passado pelo input escondido do formulario via $_POST.

if (!$admin) {   // Verifica se o retorno da variável $admin é falso. O que representa que não foi encontrado nenhum administrador com o cpf passado pelo input do formulário via $_POST.
    Middleware::redirecionar('/views/admin/password/recuperarSenha.php', 'danger', 'CPF inválido!');    // Redireciona para a página de recuperação de senha dos administradores com uma mensagem (informando o erro) armazenada em uma sessão.
}

$token = Administrador::gerarToken();   // Gera um token baseado em criptografia md5 de um identificador único.


// Monta o array com o novo token do administrador.
$arrayAdmin = [
    'token' => $token
];

$adminModel->update($arrayAdmin, intval($admin['id']));   // Atualiza o administrador inserindo o novo token do mesmo no banco de dados.


$assunto = 'Recuperar senha - Painel Administrativo da Graphic';   //Armazena a mensagem do assunto do email que vai ser enviado em uma variável.

  // Cria uma sessão com uma mensagem passando as informações para o administrador que passará pelo processo de recuperação de senha.
$_SESSION['recuperar_senha_admin'] = "Olá {$admin['nome']}, um pedido de redefinição de senha foi solicitado para a sua conta 
 {$admin['email']} no Painel administrativo da Graphic. Para confirmar este pedido e definir uma nova senha para sua conta, por favor, clique no link abaixo: <br><br> 
 <a href=\"http://localhost/mscode/challengetwo/views/admin/password/redefinirSenha.php?token=$token\">Redefinir senha</a>";


 // Aqui utiliza-se do buffer de saida para armazenar a página que queremos mandar no email em uma variável.
ob_start();     // Inicializa o output buffer(região da memória onde os dados ficam armazenados temporariamente até que sejam despejados para a aplicação) e bloqueia qualquer saída para o navegador. Aqui o que estiver abaixo do ob_start será guardado temporiariamente no buffer.
include('../../../../views/admin/emails/mensagemAdminRedefenirSenha.php');     // Inclui o arquivo que contem o html que seŕa responsavel por estruturar o email que será enviado. 
$conteudoHtml = ob_get_clean();     // Obtêm o conteúdo do buffer e armazena na variavel $conteudoHtml, e encerra o buffer de saida.


$emailEnviado = $emailModel->enviarEmail($admin['email'], $assunto, $conteudoHtml);   // Envia um email para o endereço de email do administrador encontrado pelo cpf no banco de dados. 

if ($emailEnviado) {    //Verifica se a variavel $emailEnviado é igual a verdadeiro.
    Middleware::redirecionar('/views/admin/password/recuperarSenha.php', 'success', 'Um link de redefinição de senha foi enviado para seu e-mail!');    // Redireciona para a página de recuperação de senha dos administradores com uma mensagem (informando do contato para redefinição de senha por e-mail).
}
