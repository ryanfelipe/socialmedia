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
            $this->loadView("software/socialmedia/login/partes/general/sobre");
        }elseif($page == 'politica'){
            $this->loadView("software/socialmedia/login/partes/general/politica");
        }elseif('termos'){
            $this->loadView("software/socialmedia/login/partes/general/termos");
        }else{
            echo 'Erro ao carregar conteúdo!';
        }        
    }
}