<?php
namespace Application\Model;
use Geekx\Model;
use Geekx\Common;

class Ed extends Model {
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
    /*
    |-----------------------------------------------------------------------------
    |Faz a listagem do conteúdo de uma categoria baseado no nome dessa categoria
    |-----------------------------------------------------------------------------
    */
    public function listarConteudoPorCategoria($categoria){
        $id = (int)$this->getIdByCategoria($categoria)['id'];
        return $this->db->select("SELECT * FROM {$this->tabela01} WHERE categoria_edu_id = :categoria_edu_id", array(':categoria_edu_id' => $id));
    }
    /*
    |-----------------------------------------------------
    |Rretorna o id de uma categoria a partir do seu nome
    |-----------------------------------------------------
    */
    public function getIdByCategoria($categoria){
        return $this->db->select("SELECT id FROM {$this->tabela02} WHERE categoria = :categoria", array(':categoria' => $categoria), FALSE);
    }
    /*
    |---------------------------------------------------
    |Carrega o link do material para ser acessado
    |--------------------------------------------------
    */
    public function verMaterial($id){
        return $this->db->select("SELECT link FROM {$this->tabela01} WHERE id = :id", array(':id' => $id), FALSE);
    }
    public function editarConteudo($id){
        
    }
    public function atualizaConteudo($id, $dados = array()) {
        $this->id = $id;
        $where = "id = " . (int) $this->id;
        return $this->db->update($this->tabela01, $dados, $where);
    }
}