<?php

namespace App\Models;


trait Tools
{
    public static function removeMascara(string $inputComMascara): string
    {
        return preg_replace("/[^0-9]/", '', $inputComMascara);
    }

    public static function formataCpfeCnpj(string $cpfcnpj): string
    {
        if (strlen($cpfcnpj) == 11) {

            return $cpfcnpj = substr($cpfcnpj, 0, 3) . '.' . substr($cpfcnpj, 3, 3) . '.' . substr($cpfcnpj, 6, 3) . '-' . substr($cpfcnpj, 9, 2);
        }
        if (strlen($cpfcnpj) == 14) {

            return $cpfcnpj = substr($cpfcnpj, 0, 2) . '.' . substr($cpfcnpj, 2, 3) . '.' . substr($cpfcnpj, 5, 3) . '/' . substr($cpfcnpj, 8, 4) . '-' . substr($cpfcnpj, 12, 2);
        }
    }

    public static function gerarToken()
  {
     return $token = md5(uniqid());
  }

}
