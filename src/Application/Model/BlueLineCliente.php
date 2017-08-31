<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class BlueLineCliente extends Model {

    private $tabela = "blueline_cli_pfisica";

    public function add($dados = array()) {
        return $this->db->insert($this->tabela, $dados);
    }

    public function update($id, $usuario = array()) {

        $where = "id = " . (int) $id;

        return $this->db->update($this->tabela, $usuario, $where);
    }

    public function getByEmail($email) {
        $email = (string) $email;
        return $this->db->select("SELECT * FROM {$this->tabela} WHERE email = :email", array(':email' => $email), FALSE);
    }

    public function getAll() {
        return $this->db->select("SELECT * FROM {$this->tabela}");
    }

    public function getByUserID($blueline_id_admin) {
        return $this->db->select("SELECT * FROM {$this->tabela} WHERE blueline_usuario_id = :blueline_usuario_id", array(':blueline_usuario_id' => $blueline_id_admin));
    }

    public function getByID($id) {
        return $this->db->select("SELECT * FROM {$this->tabela} WHERE id = :id", array(':id' => $id));
    }

}
