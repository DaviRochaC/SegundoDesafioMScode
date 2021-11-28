<?php

ini_set('display_errors', true);
error_reporting(E_ALL);


require('../../vendor/autoload.php');

use App\Models\{Cliente,Orcamento};

$clienteModel = new Cliente();
$orcamentoModel = new Orcamento();

$clientes = $clienteModel->busca();


foreach($clientes as $cliente){

    $token = Orcamento::gerarToken();

    $arrayOrcamento = [
        'titulo' => "Orcamento {$cliente['id']}",
        'valor_total' =>  doubleval(rand(100,10000))/100,
        'clientes_id' => $cliente['id'],
        'token' =>$token,
        'status_orcamento_id'=>rand(1,5),
        'pdf_url' =>"http://localhost/mscode/challengetwo/views/pdf/orcamentos.pdf",
        'administrador_id'=>$_SESSION['admin']['id']
    ];

    $orcamentoModel->create($arrayOrcamento);
}

   







