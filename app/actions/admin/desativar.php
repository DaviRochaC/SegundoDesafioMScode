<?php

//ligando o log de erro do php 
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require_once('../../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\{Auth\Middleware, Communication\Email};


Middleware::verificaAdminLogado(); // Verifica se usuario administrador está logado.
Middleware::verificaAdminMaster('/views/admin/dashboard.php'); // Verifica se o administrador é do tipo master.

Middleware::verificaCampos($_GET, array('i'), '/views/admin/gerenciarAdmin.php', 'Ocorreu um erro tente novamente!');  // Verifica se o índices passados através da variável global $_GET  são vazios ou nulos.

if (intval(base64_decode($_GET['i'])) <= 0) {  // Verifica se o valor inteiro da descriptografia em base64 do GET no indice i é menor ou igual a zero.
    Middleware::redirecionar('/views/admin/gerenciarAdmin.php', 'danger', 'Ocorreu um erro tente novamente!');  // Redireciona para a página de gerenciamento de Administradores  com uma mensagem armazenada em uma sessão. 
}

// Instâncias para utilização dos metódos.
$adminModel = new Administrador();
$emailModel = new Email();

$id = intval(base64_decode($_GET['i'])); // Armazena o valor inteiro da descriptografia em base64 do GET no indice i em uma variável.

$admin = $adminModel->busca('id', $id);  // Busca no banco de dados na tabela de administradores por alguma linha (administrador) que tenha o conteúdo da variavel $id, e armazena o retorno em uma variável.


// Cria um array passando no índice "ativo" o valor de 0, que é a representação de falso.
$arrayAdmin = [
    'ativo' => 0
];

$adminModel->update($arrayAdmin, intval($admin['id']));  // Atualiza o administrador  encontrado pelo id no banco de dados e coloca no seu indice "ativo" o valor de 0(falso).

$assunto = 'Desligamento - Painel Administrativo da Graphic'; // Armazena string em uma váriavel.

// Cria uma sessão com uma mensagem passando as informações para o administrador desativado.
$_SESSION['desligamento_admin'] = "Olá {$admin['nome']}, infelizmente sua conta desativada e você perdeu acesso ao painel administrativo da Graphic.";


// Aqui utiliza-se do buffer de saida para armazenar a pagina que queremos mandar no email em uma variável.
ob_start();  // Inicializa o output buffer(região da memória onde os dados ficam armazenados temporariamente até que sejam despejados para a aplicação) e bloqueia qualquer saída para o navegador. Aqui o que estiver abaixo do ob_start será guardado temporiariamente no buffer
include('../../../views/admin/emails/mensagemAdminDesligamento.php');  // Inclui o arquivo passado.
$conteudoHtml = ob_get_clean(); // Obtêm o conteúdo do buffer e armazena na variável $conteudoHtml, e encerra o buffer de saida.

$emailEnviado = $emailModel->enviarEmail($admin['email'], $assunto, $conteudoHtml); // Envia um email para o endereço de email do administrador que estava cadastrado no banco de dados. 

if ($emailEnviado) { // Verifica se a variável $emailEnviado é igual a verdadeiro.
    Middleware::redirecionar('/views/admin/gerenciarAdmin.php', 'success', 'Administrador deletado com sucesso!'); // Redireciona para a página de gerenciamento de Administradores  com uma mensagem armazenada em uma sessão. 
}
