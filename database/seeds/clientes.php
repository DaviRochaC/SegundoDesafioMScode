<?php

ini_set('display_errors', true);
error_reporting(E_ALL);


require('../../vendor/autoload.php');

use App\Models\Cliente;

$clienteModel = new Cliente();


for($i = 1; $i < 50; $i++){
    $arrayCliente = [
        'nome' => "cliente $i",
        'email' => "cliente$i@mail.com",
        'cpf_cnpj'  => (rand(0,1) > 0)?rand(11111111111,99999999999):rand(11111111111111,99999999999999)
    ];

    $clienteModel->create($arrayCliente);

}





