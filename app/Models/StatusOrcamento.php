<?php

namespace App\Models;


class StatusOrcamento
{

    private $db;

    public function __construct()
    {
        $this->db = new MySql('status_orcamento');
    }

    use Crud;


    
}
