<?php
namespace Application\Model;
use Geekx\Model;
use Geekx\Common;

class Edadmin extends Model {
    private $tabela01 = "educacional";
    private $tabela02 = "categoria_edu";
    
    public function listaCategorias() {
        return $this->db->select("SELECT *  FROM {$this->tabela02}");
    }
    public function adicionaCategoria($dados = array()) {
        return $this->db->insert($this->tabela02, $dados);
    }
    public function adicionaConteudo($dados = array()) {
        return $this->db->insert($this->tabela01, $dados);
    }
    public function listConteudo(){
        return $this->db->select("SELECT *  FROM {$this->tabela01}");
    }
    public function editarConteudo($id){
         $this->id = (int) $id;
        return $this->db->select("SELECT * FROM {$this->tabela01} WHERE id = :id", array(':id' => $this->id), FALSE);
    }
    public function atualizaConteudo($id, $dados = array()) {
        $this->id = $id;
        $where = "id = " . (int) $this->id;
        return $this->db->update($this->tabela01, $dados, $where);
    }
}