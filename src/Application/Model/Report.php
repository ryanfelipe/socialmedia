<?php
namespace Application\Model;
use Geekx\Model;
use Geekx\Common;

class Report extends Model {
    private $tabela_report = "report";    
    /*
    |-----------------------------------------------------------
    |Cadastra um convite enviado para um usuário não cadastrado.
    |-----------------------------------------------------------
    */
    public function insertBug($bug = array()) {
        return $this->db->insert($this->tabela_report, $bug);
    }      
}