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

  use Tools;



  /**
   * Função para gerar senha. Usada nesse projeto apenas para ações dos administradores que tem acesso ao Sistema de Orçamentos.
   * @param int $tamanho Tamanho que você deseja que a senha seja gerada (quantidade de caracteres).
   * @return string
   */
  public static function gerarSenha(int $tamanho): string
  {
    $senha = null;  //iniciando uma variavel e atribuindo a ela um valor nulo.
    $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
    $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
    $nu = "0123456789"; // $nu contem os números
    $si = "!@#$%&*()_+="; // $si contem os símbolos

    $senha .= str_shuffle($ma);  //variável $nu é embaralhada e adicionada para a variável $senha
    $senha .= str_shuffle($mi); // variável $nu é embaralhada e adicionada para a variável $senha
    $senha .= str_shuffle($nu); // variável $nu é embaralhada e adicionada para a variável $senha
    $senha .= str_shuffle($si); // variável $nu é embaralhada e adicionada para a variável $senha
    $senha =  str_shuffle($senha);  //a variavel senha é embaralhada 

    return substr($senha, rand(0, (strlen($senha) - $tamanho)), $tamanho); // retorna uma string embaralhada de tamanho determinado pelo parâmetro $tamanho que se encontra na variavel $senha. Onde os caracteres retornados são definidos pela posição de um numero escrito pelo rand entre o tamanho da variavel $senha (72) menos a variavel $tamanho.
  }
}
