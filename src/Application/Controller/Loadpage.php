<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Loadpage extends Controller {
    public function __construct() {
        parent::__construct();
    }
    public function main() {        
        Common::redir('index');
    }
    /*
    |--------------------------------------
    |Carrega a página desejada.------------
    |--------------------------------------
    */
    public function loadPagesLogin($page) {        
        if($page == 'sobre'){
            $this->loadView("software/social/login/partes/geral/sobre");
        }elseif($page == 'politica'){
            $this->loadView("software/social/login/partes/geral/politica");
        }elseif('termos'){
            $this->loadView("software/social/login/partes/geral/termos");
        }else{
            echo 'Erro ao carregar conteúdo!';
        }        
    }
}