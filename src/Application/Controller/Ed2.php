<?php

namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Ed2 extends Controller {
    public function __construct() {
        parent::__construct();  

        if (!Session::get("logado")) {

            Session::destroy();
            Common::redir('login');

        } else if (Session::get("logado")) {
            $this->loadModel('Application\Model\Ed', 'ed');

        } else {
            
        }
    }
    /*
    |-------------------------------------
    |Método principal da aplicação--------
    |-------------------------------------
    */
    public function main() {
        $dados['categorias'] = $this->ed->listaCategorias();
        $this->loadView("ed2/index", $dados);
    }
    public function listarConteudoPorCategoria($categoria = ""){
        if($categoria == ""){
            Session::set("erro", "A categoria passada não pode estar vazia.");
            $this->loadView("ed2/index");
            Session::delete("erro");
        }else{
            Session::set("load", "Listagem de Conteúdo");
            $conteudo['dados'] = $this->ed->listarConteudoPorCategoria($categoria);
            if($conteudo['dados'] == null){
                Session::set("loadError", "Não existem dados para essa categoria.");
                $this->loadView("ed2/index", $conteudo);
                Session::delete("load");
                Session::delete("loadError");
            }else{
                $this->loadView("ed2/index", $conteudo);
                Session::delete("load");
            }            
        }
    }
    public function verMaterial($id = ""){
        Session::set("loadMaterial", "Viasualização do Material");
        $material['dados'] = $this->ed->verMaterial($id);
        $this->loadView("ed2/index", $material);
        Session::delete("loadMaterial");
    }
}