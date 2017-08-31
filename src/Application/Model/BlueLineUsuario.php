<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class BlueLineUsuario extends Model {

    private $tabela = "blueline_usuario";

    public function add($usuario = array()) {
        
        return $this->db->insert($this->tabela, $usuario);
    }

    public function update($id, $usuario = array()) {

        $where = "id = " . (int) $id;

        return $this->db->update($this->tabela, $usuario, $where);
    }

    public function getByEmail($_email) {
        $email = (string) $_email;
        return $this->db->select("SELECT * FROM {$this->tabela} WHERE email = :email", array(':email' => $email), FALSE);
    }

    public function getAllUsers() {
        return $this->db->select("SELECT * FROM {$this->tabela}");
    }

    public function getById($dado) {
        $id = (int) $dado;
        return $this->db->select("SELECT nome, sobrenome, razao_social, email, cpf_cnpj, ramo_atuacao FROM {$this->tabela} WHERE id = :id", array(':id' => $id), FALSE);
    }

    public function getEmailById($dado) {
        $id = (int) $dado;
        return $this->db->select("SELECT email FROM {$this->tabela} WHERE id = :id", array(':id' => $id), FALSE);
    }

    public function getEmailByEmail($email) {
        $this->$email = (string) $email;
        return $this->db->select("SELECT email FROM {$this->tabela} WHERE email = :email", array(':email' => $this->$email), FALSE);
    }

    public function getIdByEmail($_email) {
        $email = (string) $_email;
        return $this->db->select("SELECT id FROM {$this->tabela} WHERE email = :email", array(':email' => $email), FALSE);
    }

    public function validarUsuario($email, $senha) {

        if (
                Common::validarEmBranco($email) ||
                Common::validarEmBranco($senha) ||
                !Common::validarEmail($email)
        ) {
            return "Preencha corretamente os dados.";
        }

        // Usuário foi encontrado no banco de dados?
        $where = array(
            ':email' => $email,
            ':senha' => md5($senha)
        );

        $encontrou_dados = $this->db->select("SELECT id, nome, email, senha FROM {$this->tabela} WHERE email = :email AND senha = :senha", $where, FALSE);

        if ($encontrou_dados) {

            $dados = $encontrou_dados;
            return $dados;
        } else {

            return FALSE;
        }
    }

}
