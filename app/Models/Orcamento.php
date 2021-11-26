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


  /**
   * Função para verificar se um arquivo foi setado.
   * @param string $nomeArquivo Nome do arquivo.
   * @param int $tamanhoArquivo Tamanho do arquivo.
   * @return bool
   */
  public static function verificaArquivoSetado( string $nomeArquivo, int $tamanhoArquivo): bool
  {
    if (!isset($nomeArquivo) or empty($nomeArquivo)) {  // Verifica se o parâmetro $nomeArquivo é nulo ou vazio.
      return false;       // Retorna falso.
    }

    if (intval($tamanhoArquivo) <= 0) {  // Verifica se o valor inteiro do  parâmetro $tamanhoArquivo é menor ou igual a zero.
      return false; // Retorna falso.
    }

    return true;  // Retorna verdadeiro.
  }
}
