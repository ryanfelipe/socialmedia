<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class BlueLineEndereco extends Model {

    private $tabela_endereco = "blueline_endereco";
    private $tabela_estado = "blueline_estado";
    private $tabela_cidade = "blueline_cidade";

    public function add($dados = array()) {

        return $this->db->insert($this->tabela_endereco, $dados);
    }

    public function getAllEstados() {

        return $this->db->select("select * from {$this->tabela_estado}");
    }

    public function getAllCidades($estado) {

        $est = (int) $estado;
        return $this->db->select("select id, nome from {$this->tabela_cidade} where blueline_estado_id = :est", array(':est' => $est));
    }

    public function getEndCliByID($blueline_cli_pfisica_id) {
        return $this->db->select("SELECT * FROM {$this->tabela_endereco} WHERE blueline_cli_pfisica_id = :blueline_cli_pfisica_id", array(':blueline_cli_pfisica_id' => $blueline_cli_pfisica_id));
    }

}
