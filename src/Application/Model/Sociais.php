<?php

namespace Application\Model;
use Geekx\Model;
use Geekx\Common;

class Sociais extends Model {
    private $tabela = "social"; 

    public function getById($id) {
        $this->id = (int) $id;
        return $this->db->select("SELECT * FROM {$this->tabela} WHERE usuario_id = :id", array(':id' => $this->id), FALSE);
    }
    public function cadastrar($dados = array()) {
        return $this->db->insert($this->tabela, $dados);
    }
    /*
    |----------------------------------------------------------
    |Atualiza os dados do usuário.-----------------------------
    |----------------------------------------------------------
    */
    public function atualizar($id, $dados = array()) {
        $this->id = $id;
        $where = "usuario_id = " . (int) $this->id;
        return $this->db->update($this->tabela, $dados, $where);
    }
}