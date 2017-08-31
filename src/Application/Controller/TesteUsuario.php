<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class TesteUsuario extends Controller {
    public function __construct() {
        parent::__construct();
        $this->loadModel('Application\Model\Usuario', 'usuario');
        $this->loadModel('Application\Model\Convite', 'convite');
        $this->loadModel('Application\Model\Colaborador', 'colaborador');
        $this->loadModel('Application\Model\Invest', 'invest');
    }
    /*
    |-------------------------------------
    |Método principal da aplicação--------
    |-------------------------------------
    */
    public function main() {
        if (Session::get("id_usuario")) {
            Common::redir('index');
        } else if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
    }
    public function getNumUsers(){        
        $numUsers = $this->usuario->getNumUsers();        
        echo($numUsers[0]['numUsers']);
    }
}