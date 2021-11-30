<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('America/Sao_Paulo'); //Alterando a hora para o padrão da zona da cidade de São Paulo.

require_once('../../../../vendor/autoload.php');

use App\Models\Cliente;
use App\Models\Services\Auth\Middleware;


Middleware::verificaAdminLogado();    // Verifica se usuario administrador está logado.

// Obs: A variável global $_POST no indice 'i' contem o id do administrador criptografado em base64.
if (intval(base64_decode($_POST['i'])) <= 0) {     // Verifica se o valor inteiro da descriptografia em base64 do GET no indice i é menor ou igual a zero.
    Middleware::redirecionar('/views/admin/clientes/gerenciarClientes.php', 'danger', 'Ocorreu um erro tente novamente!');    // Redireciona para a página de edição de clientes com uma mensagem (informando o erro) armazenada em uma sessão.
}

Middleware::verificaCampos($_POST, array('nome', 'cpf_cnpj', 'email'), '/views/admin/clientes/editar.php?i=' . $_POST['i'], 'Todos os campos são obrigatórios');   // Verifica se o inputs vindos do formulário via POST são vazios ou nulos.

$clienteModel = new Cliente();     // Instância da classe Cliente para utilização de seus metódos.

$id = intval(base64_decode($_POST['i']));     // Armazena o valor inteiro da descriptografia em base64 do GET no indice i em uma variável.

$cliente = $clienteModel->busca('id', $id);    // Busca no banco de dados na tabela de clientes por alguma linha (cliente) que tenha o conteúdo da variavel $id, e armazena o retorno em uma variável.

if (!$cliente) {     // Verifica se o retorno da variável $cliente é falso. O que representa que não foi encontrado nenhum cliente com o id  passado pelo $_POST.
    Middleware::redirecionar('/views/admin/clientes/gerenciarClientes.php', 'danger', 'Ocorreu um erro tente novamente!');  // Redireciona para a página de edição de clientes com uma mensagem (informando o erro) armazenada em uma sessão.
}


$cpfOuCnpj = Cliente::removeMascara(htmlspecialchars($_POST['cpf_cnpj']));   // Remove a máscara do input do cpf0uCnpj e amarazena em uma variável.

if (strlen($cpfOuCnpj) != 11 and strlen($cpfOuCnpj) != 14) {    // Verifica se o tamanho da string $cpfOucnpj é diferente de 11 e diferente de 14, pq sem a mascara esses são os dois únicos tamanhos possiveis cpf(11) cnpj(14).
    Middleware::redirecionar('/views/admin/clientes/editar.php?i=' . $_POST['i'], 'danger', 'CPF ou CNPJ inválido'); // Redireciona para a página de edição de clientes com uma mensagem (informando o erro) armazenada em uma sessão.
}

$cpfOuCnpjValido = Cliente::verificaCnpjOuCpfValido($cpfOuCnpj);   // Verifica se o cpf é valido de acordo com o cálculo nos dígitos verificadores ou se o CNPJ é existente na receita federal via API.

if(!$cpfOuCnpjValido){   // Verifica se o retorno da variável $cpfOUCnpjValido é falso. O que representa que o cpf é inválido ou o cnpj é inexistente.
    Middleware::redirecionar('/views/admin/clientes/editar.php?i=' . $_POST['i'], 'danger', 'CPF ou CNPJ inválido'); // Redireciona para a página de edição de clientes com uma mensagem (informando o erro) armazenada em uma sessão.
}


$emailJaCadastradoNoBanco = $clienteModel->busca('email', htmlspecialchars($_POST['email']));    // Busca a string do input do email no banco de dados e armazena o retorno em uma variável.
$cpfOuCnpjJaCadastradoNoBanco = $clienteModel->busca('cpf_cnpj', $cpfOuCnpj);     // Busca a string da váriavel $cpfOuCnpj no banco de dados e armazena o retorno em uma variável.


if ($emailJaCadastradoNoBanco and $emailJaCadastradoNoBanco['id'] != $cliente['id']) {    // Verifica se a variável $emailJaCadastradoNoBanco é verdadeira, e se o id do cliente que foi encontrado é diferente do id do cliente que está sendo editado. Pois se o id for diferente a duplicação de dados que nao é permitida aconteceria.
    Middleware::redirecionar('/views/admin/clientes/editar.php?i=' . $_POST['i'], 'danger', 'Já existe um cliente vinculado ao email informado'); // Redireciona para a página de edição de clientes com uma mensagem (informando o erro) armazenada em uma sessão.
}

if ($cpfOuCnpjJaCadastradoNoBanco and $cpfOuCnpjJaCadastradoNoBanco['id'] != $cliente['id']) {    // Verifica se a variável $cpfOuCnpjJaCadastradoNoBanco é verdadeira, e se o id do cliente que foi encontrado é diferente do id do cliente  que está sendo editado. Pois se o id for diferente a duplicação de dados que nao é permitida aconteceria.
    Middleware::redirecionar('/views/admin/clientes/editar.php?i=' . $_POST['i'], 'danger', 'Já existe um cliente vinculado ao CPF ou CNPJ informado'); // Redireciona para a página de edição de clientes com uma mensagem (informando o erro) armazenada em uma sessão.
}

// Monta o array com os dados atualizados do Cliente vindos dos inputs do formulário.
$arrayCliente = [
    'nome' => htmlspecialchars($_POST['nome']),
    'email' => htmlspecialchars($_POST['email']),
    'cpf_cnpj' => $cpfOuCnpj,
];

$cliente = $clienteModel->update($arrayCliente, intval($cliente['id']));  // Atualiza os dados do cliente no banco de dados.

Middleware::redirecionar('/views/admin/clientes/gerenciarClientes.php', 'success', 'Cliente editado com sucesso!');     // Redireciona para a página de edição de clientes com uma mensagem (informando o sucesso da ação) armazenada em uma sessão.
