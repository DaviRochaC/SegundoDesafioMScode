<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('America/Sao_Paulo'); //Alterando a hora para o padrão da zona da cidade de São Paulo.

require_once('../../../../vendor/autoload.php');


use App\Models\{Orcamento, Cliente};
use App\Models\Services\{Auth\Middleware, Communication\Email};


Middleware::verificaAdminLogado();    // Verifica se usuario administrador está logado.
Middleware::verificaCampos($_POST, array('titulo', 'valor', 'clientes_id'), '/views/admin/orcamentos/novoOrcamento.php', 'Todos os campos são obrigátorios!');     // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.
$pdfSetado = Orcamento::verificaArquivoSetado($_FILES['pdf']['name'], $_FILES['pdf']['size']);  // Verifica se o pdf foi enviado de fato.


if (!$pdfSetado) {    // Verifica se o retorno da variavel $pdfSetado é falso. O que significa que o pdf não foi enviado.
    Middleware::redirecionar('/views/admin/orcamentos/novoOrcamento.php', 'danger', 'Todos os campos são obrigátorios!'); // Redireciona para página de cadastrar um novo Orçamento com uma mensagem (informando o error) armazenada em uma sessão.
}

//instancia das classes para utilização dos métodos.
$orcamentoModel = new Orcamento();
$clienteModel = new Cliente();
$emailModel = new Email();

$extensao = strstr($_FILES['pdf']['name'], '.pdf');    // Verifica se a extensão do arquivo mandado é verdadeiramente do tipo PDF.

if (!$extensao) {   // Verifica se o retorno da variavel $extensao é falso. O que significa que o arquivo enviado não é do tipo PDF.
    Middleware::redirecionar('/views/admin/orcamentos/novoOrcamento.php', 'danger', 'Por favor, envie o arquivo na extensão .pdf'); // Redireciona para página de cadastrar um novo Orçamento com uma mensagem (informando o error) armazenada em uma sessão.
}

//renomeia o nome do pdf enviado
$pdf = time() . '.pdf';
$_FILES['pdf']['name'] = $pdf;

if ($_FILES['pdf']['size'] > (1024 * 1024 * 5)) {      // Verifica se o tamanho do pdf enviado é maior que 5MB.
    MIddleware::redirecionar('/views/admin/orcamentos/novoOrcamento.php', 'danger', 'arquivo enviado é muito grande, envie arquivos de até 5 MB');
}

move_uploaded_file($_FILES['pdf']['tmp_name'], "/opt/lampp/htdocs/mscode/challengetwo/views/pdf/{$_FILES['pdf']['name']}");     // Salva na pasta "PDF" o pdf enviado no formúlario.

$urlPdf = "http://localhost/mscode/challengetwo/views/pdf/{$_FILES['pdf']['name']}";    // Armazena em uma variavel a URL do pdf salvo na pasta.


$cliente = $clienteModel->busca('id', intval($_POST['clientes_id']));     //Busca o cliente em que o orçamento vai estar associado, através do  id passado pelo formulário via $_POST, armazenando-o em uma variável.
$valor = Orcamento::removeMascara(htmlspecialchars($_POST['valor']));    //Remove a máscara do conteúdo do input do valor do orçamento, armazenando o mesmo em uma variável.
$valor = doubleval($valor) / 100;     //Transforma o conteúdo da variável $valor em um numero do tipo float e divindo por 100.
$token = Orcamento::gerarToken();     // Gera um token baseado em criptografia md5 de um identificador único.

//Montando o array com os dados do novo Orçamento.
$arrayOrcamento = [
    'titulo' => htmlspecialchars($_POST['titulo']),
    'pdf_url' => $urlPdf,
    'clientes_id' => intval($_POST['clientes_id']),
    'valor_total' => $valor,
    'status_orcamento_id' => 1,
    'token' => $token,
    'administradores_id' => intval($_SESSION['admin']['id'])
];

$orcamentoModel->create($arrayOrcamento);   // Cadastrando o novo Orçamento no Banco de Dados.

$assunto = 'Orçamento - Ghapic';     // Armazena a mensagem do assunto do email que vai ser enviado em uma variável.

// Cria um sessão que possui uma mensagem com o link para a avaliaçao do orçamento, que será  enviadas por e-mail ao cliente vinculado ao mesmo.
$_SESSION['novo_orcamento'] = "Olá {$cliente['nome']}, segue abaixo um link para visualização e avaliação do seu orçamento na Graphic.<br><br>
<a href=\"http://localhost/mscode/challengetwo/views/admin/orcamentos/avaliacaoOrcamento.php?token=$token\">Avaliar projeto</a>";


// Aqui utiliza-se do buffer de saida para armazenar a pagina que queremos mandar no email em uma variável.
ob_start();      // Inicializa o output buffer(região da memória onde os dados ficam armazenados temporariamente até que sejam despejados para a aplicação) e bloqueia qualquer saída para o navegador. Aqui o que estiver abaixo do ob_start será guardado temporiariamente no buffer.
include('../../../../views/admin/emails/mensagemAvaliacaoOrcamento.php');    // Inclui o arquivo que contem o html que seŕa responsavel por estruturar o email que será enviado. 
$conteudoHtml = ob_get_clean();      // Obtêm o conteúdo do buffer e armazena na variável $conteudoHtml, e encerra o buffer de saida.

$emailEnviado = $emailModel->enviarEmail($cliente['email'], $assunto, $conteudoHtml);    // Envia um email para o endereço de email do cliente que está vinculado ao orçamento cadastrado.

if ($emailEnviado) {    // Verifica se a variável $emailEnviado é igual a verdadeiro.
    Middleware::redirecionar('/views/admin/orcamentos/novoOrcamento.php', 'success', 'Orcamento criado com sucesso!');  // Redireciona para pagina armazenada na variável $urlRedirecionamento com uma mensagem (informando o sucesso da ação) armazenada em uma sessão.
}
