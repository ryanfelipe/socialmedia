<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class EdAdmin extends Controller {
    public function __construct() {
        parent::__construct();
        if (!Session::get("logado_admin") && !Session::get("admin")) {
            Common::redir('signInAdmin');
        } else if (Session::get("logado_admin") && Session::get("admin")) {
            $this->loadModel('Application\Model\Admin', 'admin');
            $this->loadModel('Application\Model\Edadmin', 'edadmin');
        } else {
            Session::destroy();
            Common::redir('signInAdmin');
        }
    }
    public function main() {
        $id = (int)Session::get('id_admin');
        $admin['admin'] = $this->admin->getById($id);
        $dados['categorias'] = $this->edadmin->listaCategorias();
        $this->loadView('admin/educacional', $dados, $admin);
    }
    public function listarCategorias(){
      $id = (int)Session::get('id_admin');
      $dados['categorias'] = $this->edadmin->listaCategorias();
      $admin['admin'] = $this->admin->getById($id);
      Session::set("listaCategorias", TRUE);
      $this->loadView('admin/educacional', $admin, $dados);
      die;
    }
    /*
    |------------------------------------------------
    |Abre a página para adicionar uma nova categoria.
    |------------------------------------------------
    */
    public function addCategoria($session = ""){

        if($session !== "" && $session == "categoria_sucesso"){
            $id = (int)Session::get('id_admin');
            $admin['admin'] = $this->admin->getById($id);
            Session::set("categoria_sucesso", "Categoria adicionada com sucesso!");
            Session::set("add_categoria", TRUE);
            $this->loadView('admin/educacional', $admin);
        }
        $id = (int)Session::get('id_admin');
        $admin['admin'] = $this->admin->getById($id);
        Session::set("add_categoria", TRUE);
        $this->loadView('admin/educacional', $admin);
    }
    /*
    |----------------------------------------------------
    |Abre a página responsável pela adição de conteúdo.
    |----------------------------------------------------
    */
    public function addConteudo($session = ""){
        if($session !== "" && $session == "conteudo_adicionado"){
            $id = (int)Session::get('id_admin');
            $admin['admin'] = $this->admin->getById($id);
            Session::set("conteudo_adicionado", "Conteúdo adicionado com sucesso!");
            $dados['categorias'] = $this->edadmin->listaCategorias();
            $this->loadView('admin/educacional', $dados, $admin);
        }
        $id = (int)Session::get('id_admin');
        $admin['admin'] = $this->admin->getById($id);
        $dados['categorias'] = $this->edadmin->listaCategorias();
        $this->loadView('admin/educacional', $dados, $admin);
    }
    /*
    |----------------------------------------------
    |Adiciona uma nova categoria ao banco de dados.
    |----------------------------------------------
    */
    public function adicionaCategoria(){
        $categoria = (string)filter_input(INPUT_POST, 'categoria');
        $descricao = (string)filter_input(INPUT_POST, 'descricao');
        $categoria_nome = array(
            'categoria' => (string)$categoria,
            'descricao' => (string)$descricao
        );
        $this->edadmin->adicionaCategoria($categoria_nome);
        $this->addCategoria("categoria_sucesso");
    }
    public function adicionaConteudo(){
        $categoria = (int)filter_input(INPUT_POST, 'categoria');
        $titulo = (string)filter_input(INPUT_POST, 'titulo');
        $descricao = (string)filter_input(INPUT_POST, 'descricao');
        $link = (string)filter_input(INPUT_POST, 'link');

        $dados = array(
            'titulo' => $titulo,
            'descricao' => $descricao,
            'link' => $link,
            'categoria_edu_id' => $categoria
        );

        $retorno = $this->edadmin->adicionaConteudo($dados);
        if($retorno){
            $this->addConteudo("conteudo_adicionado");
        }
    }
    /*
    |----------------------------------------------------
    |Abre a página com a listagem dos conteúdos.
    |----------------------------------------------------
    */
    public function listConteudo(){
        $id = (int)Session::get('id_admin');
        $admin['admin'] = $this->admin->getById($id);
        $conteudo['educacional'] = $this->edadmin->listConteudo();
        Session::set("list_conteudo", TRUE);
        $this->loadView('admin/educacional', $admin, $conteudo);
    }
    /*
    |--------------------------------------------------------------------
    |Abre a página de edição e também edita o conteúdo em outra chamada
    |--------------------------------------------------------------------
    */
    public function editarConteudo($id_conteudo = ""){
        $update = (int)filter_input(INPUT_POST, 'update');
        /*
        |-------------------------------
        |Se tem id edita, caso contrário abre a página
        |-------------------------------
        */
        if($id_conteudo !== "" && !$update){
            $id = (int)Session::get('id_admin');
            $admin['admin'] = $this->admin->getById($id);
            $row['educacional'] = $this->edadmin->editarConteudo($id_conteudo);
            $categorias['categorias'] = $this->edadmin->listaCategorias();
            Session::set("edit_conteudo", TRUE);
            $this->loadView('admin/educacional', $admin, $row, $categorias);
        /*
        |----------------------------------------------------------------------------
        |Caso exista a permissão para atualizar então os dados são salvos no sistema
        |----------------------------------------------------------------------------
        */
        }else if($id_conteudo !== "" && $update){
            $categoria = (int)filter_input(INPUT_POST, 'categoria');
            $titulo = (string)filter_input(INPUT_POST, 'titulo');
            $descricao = (string)filter_input(INPUT_POST, 'descricao');
            $link = (string)filter_input(INPUT_POST, 'link');

            $dados = array(
                'titulo' => $titulo,
                'descricao' => $descricao,
                'link' => $link,
                'categoria_edu_id' => $categoria
            );

            $update = $this->edadmin->atualizaConteudo($id_conteudo, $dados);

            if($update){
                $id = (int)Session::get('id_admin');
                $admin['admin'] = $this->admin->getById($id);
                $row['educacional'] = $this->edadmin->editarConteudo($id_conteudo);
                $categorias['categorias'] = $this->edadmin->listaCategorias();
                Session::set("edit_conteudo", TRUE);
                Session::set("success_update", "Dados atualizados com sucesso!");
                $this->loadView('admin/educacional', $admin, $row, $categorias);
            }else{
                $id = (int)Session::get('id_admin');
                $admin['admin'] = $this->admin->getById($id);
                $row['educacional'] = $this->edadmin->editarConteudo($id_conteudo);
                $categorias['categorias'] = $this->edadmin->listaCategorias();
                Session::set("edit_conteudo", TRUE);
                Session::set("erro_update", "Erro ao atualizar dados!");
                $this->loadView('admin/educacional', $admin, $row, $categorias);
            }
        }
    }
}
