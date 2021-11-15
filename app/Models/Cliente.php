<?php

namespace App\Models;



class Cliente{

  
  private $db;

  public function __construct()
  {
    $this->db = new MySql('clientes');
  }

  use Crud;

  public function formataCpfeCnpj($cpfcnpj)
  {
    if (strlen($cpfcnpj) == 14) {

      return preg_replace("/[^0-9]/", '',$cpfcnpj);
    }
    if (strlen($cpfcnpj) == 18) {

        return preg_replace("/[^0-9]/", '', $cpfcnpj);
      }
  }


 


    
}
