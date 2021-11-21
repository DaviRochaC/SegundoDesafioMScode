<?php

namespace App\Models;


class Orcamento
{



  private $db;

  public function __construct()
  {
    $this->db = new MySql('orcamentos');
  }

  use Crud;
  
  use Tools;



  public static function verificaFileSetado($file, $tamanhoDofile)
  {
    if (!isset($file) or empty($file)) {
      return false;
    }

    if (intval($tamanhoDofile) <= 0) {
      return false;
    }

    return true;
  }


}
