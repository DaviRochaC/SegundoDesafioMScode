<?php

namespace App\Models;



class Cliente
{


  private $db;

  public function __construct()
  {
    $this->db = new MySql('clientes');
  }

  use Crud;

  use Tools;
}
