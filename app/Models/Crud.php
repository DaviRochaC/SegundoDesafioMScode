<?php

namespace App\Models;

trait Crud
{

    /**
     * Função para cadastrar dados no banco de dados.
     * @param array $dados Dados a serem inseridos no banco de dados.
     * @return bool 
     */
    public function create(array $dados): bool
    {
        $dados['criado_em'] = date('Y-m-d H:i:s');  // O indice criado_em recebe o horario atual da data do cadastro dos dados.
        $dados['editado_em'] = date('Y-m-d H:i:s'); // O indice editado_em recebe o horario atual da data do cadastro dos dados.

        return $this->db->inserir($dados);  // Realiza o cadastro dos dados no banco.
    }

    /**
     * Função para atualizar dados no banco de dados.
     * @param array $dados  Dados atualizados para inserção no banco de dados.
     * @param int $id  Identificador único da linha a ser encontrada para a atualização dos dados.
     * @return bool
     */
    public function update(array $dados, int $id): bool

    {
        $where = "id = $id";                          // Monta o where com o id passado.
        $dados['editado_em'] = date('Y-m-d H:i:s');   // O indice editado_em recebe o  horario atual da data da atualizaçao dos dados.
        return $this->db->atualizar($dados, $where);  // Atualiza os dados no banco de acordo com o where.
    }

    /**
     * Função para realizar buscas no banco de dados.
     * @param string $colunaDaTabela Coluna na qual será realizada a consulta no banco de dados.
     * @param mixed $itemDeBusca O que será procurado na coluna informada.
     * @param bool $primeiraLinha Define se o retorno da função será apenas da primeira linha ou de todas as linhas correspondente a busca realizada pelo where no banco de dados.
     * @return bool|array 
     */
    public function busca(string $colunaDaTabela = null, mixed $itemDeBusca = null, bool $primeiraLinha  = true): bool|array
    {

        if ($colunaDaTabela != null and $itemDeBusca != null) { // Verifica se os dois primeiros parâmetros são diferentes de nulo.


            $where = "$colunaDaTabela = '$itemDeBusca'";   // Monta o where com os valores passados.

            $busca = $this->db->buscar($where);   // Realiza a busca no banco de dados de acordo com o where e armazena na variavel.

            if (count($busca) > 0) {  // Verifica quantidade de linhas retornadas.
                if ($primeiraLinha) { // Verifica se o parâmetro $primeiraLinha é verdadeiro.
                    return $busca[0]; // Retorna primeira linha da busca de acordo com where passado.
                }
                return $busca;  // Retorna todas as linhas correspondente ao where passado.
            } else {
                return false; // Retorna falso.
            }
        }
        if ($colunaDaTabela == null and $itemDeBusca == null) { // Verifica se os dois primeiros parâmetros são nulos.
            return $this->db->buscar();   // Retorna a busca no banco de dados sem where especifico, nesse caso especifico vai retornar todas as linhas de determinada tabela.
        }

        return false; // Retorna falso. Esse retorno ira acontecer quando um dos dois primeiros parâmetros é nulo e o outro não.
    }



    /**
     * Função para deletar dados no banco de dados.
     * @param int $id Identificador único da linha a ser encontrada para a atualização dos dados.
     * @return bool
     */
    public function delete(int $id): bool
    {
        $where = "id = $id";                 // Monta o where com o id passado.
        return $this->db->deletar($where);   // Deleta os dados no banco de acordo com o where.
    }
}
