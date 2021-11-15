<?php

namespace App\Models;

trait Crud
{

    public function create(array $dados)
    {
        $dados['criado_em'] = date('Y-m-d H:i:s');
        $dados['editado_em'] = date('Y-m-d H:i:s');

        return $this->db->inserir($dados);
    }

    public function update(array $dados, mixed $id)
    {
        $where = "id = $id";
        $dados['editado_em'] = date('Y-m-d H:i:s');
        return $this->db->atualizar($dados, $where);
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

    public function delete($id)
    {
        $where = "id = $id";
        return $this->db->deletar($where);
    }
}
