<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class Colaborador extends Model {
    private $tabela_usuario_tem_usuario = "usuario_tem_usuario";
    private $tabela_usuario = "usuario";

    public function getById($id) {
        $this->id = (int) $id;

        return $this->db->select("select * from ("
                        . "select a.id, a.nome, a.sobrenome, a.logado, a.fotoperfil from usuario a "
                        . "inner join {$this->tabela_usuario_tem_usuario} u on a.id = u.usuario_anfitriao "
                        . "and u.usuario_convidado = :id "
                        . "union "
                        . "select b.id, b.nome, b.sobrenome, b.logado, b.fotoperfil from usuario b "
                        . "inner join {$this->tabela_usuario_tem_usuario} u on b.id = u.usuario_convidado "
                        . "and u.usuario_anfitriao = :id )dum order by nome asc", array(':id' => $this->id));
    }

    public function getByIdInJsonLimit($id, $limit, $offset) {
        $this->id = (int) $id;
        $this->limit = (int) $limit;
        $this->offset = (int) $offset;

        $dados = $this->db->select("select * from ("
                . "select a.id, a.nome, a.sobrenome, a.logado, a.fotoperfil, a.fotocapa, a.email from usuario a "
                . "inner join {$this->tabela_usuario_tem_usuario} u on a.id = u.usuario_anfitriao "
                . "and u.usuario_convidado = :id "
                . "union "
                . "select b.id, b.nome, b.sobrenome, b.logado, b.fotoperfil, b.fotocapa, b.email from usuario b "
                . "inner join {$this->tabela_usuario_tem_usuario} u on b.id = u.usuario_convidado "
                . "and u.usuario_anfitriao = :id )amigos order by id asc limit :limit offset :offset", array(':id' => $this->id, ':limit' => $this->limit, ':offset' => $this->offset));

        //Criptografa os campos de e-mail.      
        for ($count = 0; $count < count($dados); $count++) {
            $email_criptografado = base64_encode($dados[$count]['email']);
            $dados[$count]['email'] = $email_criptografado;
        }
        
        return $dados;
    }

    public function getNumAmigos($id) {
        $this->id = (int) $id;

        return $this->db->select("select count(*) num from ("
                        . "select a.id, a.nome, a.sobrenome, a.logado from usuario a "
                        . "inner join {$this->tabela_usuario_tem_usuario} u on a.id = u.usuario_anfitriao "
                        . "and u.usuario_convidado = :id "
                        . "union "
                        . "select b.id, b.nome, b.sobrenome, b.logado from usuario b "
                        . "inner join {$this->tabela_usuario_tem_usuario} u on b.id = u.usuario_convidado "
                        . "and u.usuario_anfitriao = :id )dum order by nome asc", array(':id' => $this->id));
    }

    public function getEmailById($id) {
        $this->id = (int) $id;
        return $this->db->select("SELECT email FROM {$this->tabela_usuario_tem_usuario} WHERE id = :id", array(':id' => $this->id), FALSE);
    }

    public function verColaborador($busca, $id_de) {
        $busca_decode = base64_decode($busca);
        $busca_decode2 = base64_decode($busca_decode);
        $busca_decode3 = base64_decode($busca_decode2);
        $busca_decode4 = base64_decode($busca_decode3);
    }
}
