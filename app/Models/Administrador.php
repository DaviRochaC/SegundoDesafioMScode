<?php

namespace App\Models;




class Administrador
{



  private $db;

  public function __construct()
  {
    $this->db = new MySql('administradores');
  }

  use Crud;


  public function limpaCpf(string $cpf): string
  {
    if (strlen($cpf) == 14) {

      return $cpf = preg_replace("/[^0-9]/", '', $cpf);
    }
  }

  public function formataCpfeCnpj(string $cpf): string
  {
    if (strlen($cpf) == 11) {

      return $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
  }



  public function gerarSenha(int $tamanho): string
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
    return substr($senha, rand(0, (strlen($senha) - $tamanho)), $tamanho);
  }
}
