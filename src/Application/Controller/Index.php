<?php
namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Index extends Controller {
    public function __construct() {
        parent::__construct();

        if (!Session::get("logado")) {
            Session::destroy();
            Common::redir('home');
        } else if (Session::get("logado") && Session::get("id_usuario")) {
            $this->loadModel('Application\Model\Usuario', 'usuario');
            $this->loadModel('Application\Model\Colaborador', 'colaborador');
        } else {
          Session::destroy();
          Common::redir('home');
        }

    }
    public function main() {
        $this->loadView("software/socialmedia/index/index");
    }
    public function logout() {
        $id_usuario = Session::get("id_usuario");
        $this->usuario->updateActiveFalse($id_usuario);
        Session::destroy();
        Common::redir('index');
    }
}
