<?php

namespace App\Models;


class Administrador
{

  private $db;

  public function __construct()
  {
    $this->db = new MySql('administradores');
  }

  public function create(array $arrayAdmin)
  {
    $arrayAdmin['criado_em'] = date('Y-m-d H:i:s');
    $arrayAdmin['editado_em'] = date('Y-m-d H:i:s');

    return $this->db->inserir($arrayAdmin);
  }

  public function update(array $arrayAdmin, mixed $id)
  {
    $where = "id = $id";
    $arrayAdmin['editado_em'] = date('Y-m-d H:i:s');
    return $this->db->atualizar($arrayAdmin, $where);
  }

  public function busca(string $colunaDaTabela, mixed $itemDeBusca): bool|array
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
    if (strlen($cpf) == 14) {

      return preg_replace("/[^0-9]/", '', $cpf);
    }
  }



  public function gerarSenha(int $tamanho)
  {
    $senha = null;
    $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
    $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
    $nu = "0123456789"; // $nu contem os números
    $si = "!@#$%&*()_+="; // $si contem os símbolos
  
      $senha .= str_shuffle($ma);  //variável $nu é embaralhada e adicionada para a variável $senha
      $senha .= str_shuffle($mi); // variável $nu é embaralhada e adicionada para a variável $senha
      $senha .= str_shuffle($nu); // variável $nu é embaralhada e adicionada para a variável $senha
      $senha .= str_shuffle($si); // variável $nu é embaralhada e adicionada para a variável $senha
      $senha =  str_shuffle($senha);  //a variavel senha é embaralhada 

    // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho a partir do rand
    //entre o tamanho da string senha menos o variavel tamanho
    return substr($senha,rand(0,(strlen($senha)-$tamanho)),$tamanho);

    
  }
}
