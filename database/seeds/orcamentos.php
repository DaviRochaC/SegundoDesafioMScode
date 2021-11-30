<?php
date_default_timezone_set('America/Sao_Paulo'); //Alterando a hora para o padrão da zona da cidade de São Paulo.
ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require('../../vendor/autoload.php');

use App\Models\{Cliente, Orcamento};

$clienteModel = new Cliente();
$orcamentoModel = new Orcamento();

$clientes = $clienteModel->busca();


$motivos = [
    "Caro Demais", 'Material Ruim', 'Não era o que eu esperava'
];


foreach ($clientes as $cliente) {

    $token = Orcamento::gerarToken();

    $arrayOrcamento = [
        'titulo' => "Orcamento {$cliente['id']}",
        'valor_total' =>  doubleval(rand(100, 10000)) / 100,
        'clientes_id' => $cliente['id'],
        'token' => $token,
        'status_orcamento_id' => rand(1, 5),
        'pdf_url' => "http://localhost/mscode/challengetwo/views/pdf/orcamentos.pdf",
        'administradores_id' => 1
    ];

    $orcamentoModel->create($arrayOrcamento);

    $orcamento = $orcamentoModel->busca('clientes_id', $cliente['id']);


    if ($orcamento['status_orcamento_id'] == 3 or $orcamento['status_orcamento_id'] == 4) {

        $arrayOrcamento = [
            'motivo_status_orcamento' => $motivos[rand(0, 2)]
        ];

        $orcamentoModel->update($arrayOrcamento,$orcamento['id']);
    }
}
