<?php

require_once('../../vendor/autoload.php');

use App\Models\StatusOrcamento;

$statusorcamentoModel = new StatusOrcamento;


$statusOrcamentos = ['Não respondido',
'Aceito','Cancelado','Rejeitado','Faturado'];

for($i = 0; $i < count($statusOrcamentos);$i++){

$arrayStatusOrcamento = [
'nome' => $statusOrcamentos[$i]
];

$statusorcamentoModel->create($arrayStatusOrcamento);


}


