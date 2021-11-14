<?php

require_once('MySql.php');

class Administrador
{

  private $db;

  public function __construct()
  {
    $this->db = new MySql('administradores');
  }

  public function create($arrayAdmin)
  {
    $arrayAdmin['criado_em'] = date('Y-m-d H:i:s');
    $arrayAdmin['editado_em'] = date('Y-m-d H:i:s');

    return $this->db->inserir($arrayAdmin);
  }

  public function update($arrayAdmin,$id)
  {
    $where = "id = $id";
    $arrayAdmin['editado_em'] = date('Y-m-d H:i:s');
    return $this->db->atualizar($arrayAdmin,$where);

  }

  public function busca(string $colunaDaTabela, $itemDeBusca)
  {

    $where = "$colunaDaTabela = '$itemDeBusca'";

    $busca = $this->db->buscar($where);

    if (count($busca) > 0) {
      return $busca[0];
    } else {
      return false;
    }
  }

  public function formatacpf($cpf)
  {
    if(strlen($cpf) == 14){

      return preg_replace("/[^0-9]/", '', $cpf);
    }
  }
}
