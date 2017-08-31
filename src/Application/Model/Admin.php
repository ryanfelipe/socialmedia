<?php
namespace Application\Model;
use Geekx\Model;
use Geekx\Common;

class Admin extends Model {
    private $tabela = "admin";
    
    public function getById($id) {
        $this->id = (int) $id;
        return $this->db->select("SELECT * FROM {$this->tabela} WHERE id = :id", array(':id' => $this->id), FALSE);
    }
    public function validaAdmin($email, $senha) {
        $this->email = (string)$email;
        $this->senha = (string)$senha;

        if (
                Common::validarEmBranco($this->email) ||
                Common::validarEmBranco($this->senha) ||
                !Common::validarEmail($this->email)
        ) {
            return "Preencha corretamente os dados.";
        }

        $where = array(
            ':email' => $this->email,
            ':senha' => md5($this->senha),
            ':status' => true,
        );

        $encontrou = $this->db->select("SELECT id FROM {$this->tabela} WHERE email = :email AND senha = :senha AND status = :status", $where, FALSE);

        if ($encontrou) {
            return $encontrou;
            die;
        } else {
            return "Sua conta não foi encontrada no sistema.";
            die;
        }
    }
}