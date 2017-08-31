<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class BlueLinePlanos extends Model {

    private $tabela = "blueline_tipo_plano";
    private $tabela2 = "blueline_plano";

    public function addPlano($plano = array()) {

        return $this->db->insert($this->tabela2, $plano);
    }

    public function getPlanoFree() {
        return $this->db->select("SELECT * FROM {$this->tabela}");
    }

    public function getTipoPlanoFree($tipo) {
        
        return $this->db->select("SELECT id FROM {$this->tabela} WHERE tipo = :tipo", array(':tipo' => $tipo));
    }

}
