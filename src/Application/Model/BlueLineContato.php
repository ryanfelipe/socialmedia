<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class BlueLineContato extends Model {

    private $tabela = "blueline_contato_pf";

    public function add($dados = array()) {

        return $this->db->insert($this->tabela, $dados);
    }

    public function getAllEstados() {

        return $this->db->select("select * from {$this->tabela}");
    }
    
    public function addmm($dados = array()) {

        return $this->db->insert($this->tabela2, $dados);
    }
    
    public function getByID($blueline_cli_pfisica_id) {
        return $this->db->select("SELECT * FROM {$this->tabela} WHERE blueline_cli_pfisica_id = :blueline_cli_pfisica_id", array(':blueline_cli_pfisica_id' => $blueline_cli_pfisica_id));
    }

}
