<?php

namespace App\Models;

trait Crud
{

    public function create(array $dados): bool //Funcao de cadastrar dados. Tendo como parametro um array de dados
    {
        $dados['criado_em'] = date('Y-m-d H:i:s');  //passando para o indice de criado_em o horario atual do cadastro
        $dados['editado_em'] = date('Y-m-d H:i:s'); //passando para o indice de editado_em o horario atual do cadastro

        return $this->db->inserir($dados);  //a funcao usa o metodo inserir da classe MySql.php para cadastrar os dados da tabela devida 
        //no banco de dados e de acordo com o processo retorna true ou false;
    }

    public function update(array $dados, int $id): bool //Funcao de atualizar dados. Tendo como seus parametros um array de dados e um variavel que seja id do tipo int.

    {
        $where = "id = $id";                          //montando nosso where, parametro de busca do metodo atualizar, funcao que usamos para conexão com o banco de dados.
        $dados['editado_em'] = date('Y-m-d H:i:s');   //passando para o indice de editado_em o horario atual da atualizaçao dos dados.
        return $this->db->atualizar($dados, $where);  // usando a funcao atualizar da classe MySql  para conexao com o banco de dados, passando como parametro o array de  dados e o 
        // item de busca. Tambem retorna true ou false dependendo do resultado de seu processo.                              

    }

    public function busca(string $colunaDaTabela = null, mixed $itemDeBusca = null, bool $limite  = true): bool|array
    {

        if ($colunaDaTabela != null and $itemDeBusca != null) {


            $where = "$colunaDaTabela = '$itemDeBusca'";

            $busca = $this->db->buscar($where);

            if (count($busca) > 0) {
                if ($limite) {
                    return $busca[0];
                }
                return $busca;
            } else {
                return false;
            }
        }
        if ($colunaDaTabela == null and $itemDeBusca == null) {
            return $this->db->buscar();
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $where = "id = $id";
        return $this->db->deletar($where);
    }
}
