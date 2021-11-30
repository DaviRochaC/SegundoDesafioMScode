<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('America/Sao_Paulo'); //Alterando a hora para o padrão da zona da cidade de São Paulo.

require_once('../../../../vendor/autoload.php');

use App\Models\Cliente;
use App\Models\Services\Auth\Middleware;


Middleware::verificaAdminLogado();   // Verifica se usuario administrador está logado.
Middleware::verificaCampos($_POST, array('nome', 'cpf_cnpj', 'email'), '/views/admin/clientes/cadastrar.php', 'Todos os campos são obrigatórios!');   // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.

$clienteModel = new Cliente();   // Instância da classe Cliente para utilização de seus metódos.


$cpfOuCnpj = Cliente::removeMascara(htmlspecialchars($_POST['cpf_cnpj']));    // Remove a máscara do input do cpf0uCnpj e amarazena em uma variável.

if (strlen($cpfOuCnpj) != 11 AND strlen($cpfOuCnpj) != 14) {    // Verifica se o tamanho da string $cpfOucnpj é diferente de 11 e diferente de 14, pq sem a mascara esses são os dois únicos tamanhos possiveis cpf(11) cnpj(14).
    Middleware::redirecionar('/views/admin/clientes/cadastrar.php', 'danger', 'CPF ou CNPJ inválido');    // Redireciona para a página do cadastro de clientes com uma mensagem informando o erro armazenada em uma sessão.
}

$cpfOuCnpjValido = Cliente::verificaCnpjOuCpfValido($cpfOuCnpj);   // Verifica se o cpf é valido de acordo com o cálculo nos dígitos verificadores ou se o CNPJ é existente na receita federal via API.

if(!$cpfOuCnpjValido){    // Verifica se o retorno da variável $cpfOUCnpjValido é falso. O que representa que o cpf é inválido ou o cnpj é inexistente.
    Middleware::redirecionar('/views/admin/clientes/cadastrar.php', 'danger', 'CPF ou CNPJ inválido');   // Redireciona para a página do cadastro de clientes com uma mensagem (informando o erro) armazenada em uma sessão.
}

$emailJaCadastradoNoBanco = $clienteModel->busca('email', htmlspecialchars($_POST['email']));   // Busca a string do input do email no banco de dados e armazena o retorno em uma variável.
$cpfOuCnpjJaCadastradoNoBanco =  $clienteModel->busca('cpf_cnpj', $cpfOuCnpj);     // Busca a string da váriavel $cpfOuCnpj no banco de dados e armazena o retorno em uma variável.

if ($emailJaCadastradoNoBanco) {   // Verifica se a variável $emailJaCadastradoNoBanco é verdadeira. O que representa que já existe alguém como esse email no banco de dados.  E essa possível duplicação nao é permitida.
    Middleware::redirecionar('/views/admin/clientes/cadastrar.php', 'danger', 'Já existe um cliente vinculado ao email informado'); // Redireciona para a página do cadastro de clientes com uma mensagem (informando o erro) armazenada em uma sessão.
}

if ($cpfOuCnpjJaCadastradoNoBanco) {   // Verifica se a variável $cpfOuCnpjJaCadastradoNoBanco é verdadeira. O que representa que já existe alguém como esse cpf ou cnpj no banco de dados.  E essa possível duplicação nao é permitida.
    Middleware::redirecionar('/views/admin/clientes/cadastrar.php', 'danger', 'Já existe um cliente vinculado ao CPF ou CNPJ informado');  // Redireciona para a página do cadastro de clientes com uma mensagem (informando o erro) armazenada em uma sessão.
}

// Monta o array dos dados do Cliente vindos dos inputs do formulário.
$arrayCliente = [
    'nome' => htmlspecialchars($_POST['nome']),
    'email' => htmlspecialchars($_POST['email']),
    'cpf_cnpj' => $cpfOuCnpj,
];


$clienteModel->create($arrayCliente);   // Insere os dados presentes no array acima no banco de dados, especificamente tabela de clientes.

Middleware::redirecionar('/views/admin/clientes/cadastrar.php', 'success', 'Cliente cadastrado com sucesso!');   // Redireciona para a página do cadastro de clientes com uma mensagem (informando o sucesso da ação) armazenada em uma sessão.
