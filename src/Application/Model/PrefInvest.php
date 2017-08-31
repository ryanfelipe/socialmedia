<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class PrefInvest extends Model {

    private $tabela = "pref_invest";

    public function getUsuarioByEmail($email) {
        $this->email = (string) $email;

        return $this->db->select("SELECT * FROM {$this->tabela_usuario} WHERE email = :email", array(':email' => $this->email), FALSE);
    }
    /*
    |-------------------------------------------
    |
    |
    */
    public function getByUserId($preferencia){
        return $this->db->select("SELECT nome, fotoperfil FROM {$this->tabela_usuario} WHERE ");
    }
}
