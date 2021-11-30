<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();


require_once('../../../../vendor/autoload.php');


use App\Models\{Orcamento, Cliente};
use App\Models\Services\{Auth\Middleware, Communication\Email};


Middleware::verificaAdminLogado(); // Verifica se usuario administrador está logado.
Middleware::verificaCampos($_GET, array('token'), '/views/admin/dashboard.php', 'Ocorreu um erro, tente novamente!'); // Verifica se o índices passados através da variável global $_GET  são vazios ou nulos.

// Instâncias para utilização dos metódos.
$orcamentoModel = new Orcamento();
$clienteModel = new Cliente();
$emailModel = new Email();

$orcamento = $orcamentoModel->busca('token', htmlspecialchars($_GET['token']));   // Buscando um orçamento no banco de dados que tenha o token passado pelo $_GET['token'] e armazenando o retorno em uma variável.


if (!$orcamento) {    // Verifica se a variável $orcamento tem o retorno igual a falso. O que significa que não foi encontrado um orcamento pelo token passado pelo $_GET['token'].
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosCriados.php', 'danger', 'Ocorreu um erro, tente novamente!'); // Redireciona para página dos Orçamentos criados.
}

$cliente = $clienteModel->busca('id', $orcamento['clientes_id']);   // Buscando o cliente vinculado ao orcamento através da chave de associação entre os dois(clientes_id).

$assunto = 'Orçamento aguardando resposta - Graphic';    // Armazena a mensagem do assunto do email que vai ser enviado em uma variável.

// Cria uma sessão com uma mensagem passando as informações para o cliente sobre a pendencia de resposta de seu orçamento. E no final passando novamente o link para página de avaliação do orçamento.
$_SESSION['orcamento_resposta_pendente'] = "Olá {$cliente['nome']}, nós da Graphic estamos passando para relembrar que seu orçamento já foi realizado. Ainda estamos aguardando sua resposta em relação ao mesmo.
Segue abaixo um link para avaliação e visualização do seu orçamento.<br><br>
<a href=\"http://localhost/mscode/challengetwo/views/admin/orcamentos/avaliacaoOrcamento.php?token={$orcamento['token']}\">Avaliar projeto</a>";


// Aqui utiliza-se do buffer de saida para armazenar a pagina que queremos mandar no email em uma variável.
ob_start();    // Inicializa o output buffer(região da memória onde os dados ficam armazenados temporariamente até que sejam despejados para a aplicação) e bloqueia qualquer saída para o navegador. Aqui o que estiver abaixo do ob_start será guardado temporiariamente no buffer.
include('../../../../views/admin/emails/mensagemClienteOrcamentoPendente.php');    // Inclui o arquivo que contem o html que seŕa responsavel por estruturar o email que será enviado. 
$conteudoHtml = ob_get_clean();    // Obtêm o conteúdo do buffer e armazena na variável $conteudoHtml, e encerra o buffer de saida.

$emailEnviado = $emailModel->enviarEmail($cliente['email'], $assunto, $conteudoHtml);  // Envia um email para o endereço de email do cliente encontrado vinculado ao orçamento armazendo em $orcamento.

if ($emailEnviado) {    // Verifica se a variável $emailEnviado é igual a verdadeiro.
    Middleware::redirecionar('/views/admin/orcamentos/listarOrcamentosCriados.php', 'success', 'Um e-mail informando a necessidade de uma resposta sobre o orçamento foi enviado ao cliente!'); // Redireciona para página dos Orçamentos criados com uma mensagem (informando o sucesso da ação) armazena em uma sessão.
}
