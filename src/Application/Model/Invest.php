<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class Invest extends Model {
    private $tabela = "pref_invest"; 

    public function cadastrar($dados = array()) {
        return $this->db->insert($this->tabela, $dados);
    }
    /*
    |----------------------------------------------------------
    |Atualiza os dados do usuário.
    |----------------------------------------------------------
    */
    public function atualizar($id, $usuario = array()) {
        $this->id = $id;
        $where = "usuario_id = " . (int) $this->id;

        return $this->db->update($this->tabela, $usuario, $where);
    }
    public function getPrefs($id){        
        $this->id = (int) $id;
        return $this->db->select("SELECT * FROM {$this->tabela} WHERE usuario_id = :usuario_id", array(':usuario_id' => $this->id), FALSE);
    }
}