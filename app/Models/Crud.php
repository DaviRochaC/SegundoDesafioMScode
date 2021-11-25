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

    /**
     * funcao para realizar buscas no banco de dados
     * @param string $colunaDaTabela coluna na qual será realizada a consulta.
     * @param string $itemDeBusca o que será procurado na coluna informada.
     * @param bool $primeiraLinha define se será buscada apenas a primeira linha correspende ao where ou todos.
     * @return bool|array 
     */
    public function busca(string $colunaDaTabela = null, mixed $itemDeBusca = null, bool $primeiraLinha  = true): bool|array
    {

        if ($colunaDaTabela != null and $itemDeBusca != null) { // Verifica se os dois primeiros parametros são diferentes de nulo.


            $where = "$colunaDaTabela = '$itemDeBusca'";   // Monta o where com os valores passados.

            $busca = $this->db->buscar($where);   // Realiza a busca no banco de dados de acordo com o where e armazena na variavel.

            if (count($busca) > 0) {  // Verifica quantidade de linhas retornadas.
                if ($primeiraLinha) { // Verifica se o paramentro $primeiraLinha é verdadeiro.
                    return $busca[0]; // Retorna primeira linha da busca de acordo com where passado.
                }
                return $busca;  // Retorna todas as linhas correspondente ao  where passado.
            } else {
                return false; // Retorna falso
            }
        }
        if ($colunaDaTabela == null and $itemDeBusca == null) { // Verifica se os dois primeiros parametros são nulos.
            return $this->db->buscar();   // Retorna a busca no banco de dados sem where especifico, nesse caso especifico vai retornar todas as linhas de determinada tabela.
        }

        return false; // Retorna falso, esse retorno ira acontecer quando um dos dois primeiros parametros é nulo e o outro não.
    }

    public function delete(int $id): bool
    {
        $where = "id = $id";
        return $this->db->deletar($where);
    }
}
