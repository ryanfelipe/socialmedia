<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Login extends Controller {
    public function __construct() {
        parent::__construct();
        if (Session::get("logado")) {
            Common::redir('index');
        }
        $this->loadModel('Application\Model\Usuario', 'usuario');
    }
    public function main() {
        Session::destroy();
        $this->loadView("software/socialmedia/login/index");
    }

    public function processar() {
        $email = (string) filter_input(INPUT_POST, 'input-email');
        $senha = (string) filter_input(INPUT_POST, 'input-senha');
        $logar = $this->usuario->validaUsuario($email, $senha);
        $id_usuario = (int) $logar['id'];

        if ($id_usuario !== 0) {
            $this->usuario->updateActiveTrue($id_usuario);
            Session::set("logado", TRUE);
            Session::set("id_usuario", $id_usuario);
            Common::redir('index');
        } else {
            Session::set("erro", $logar);
            Common::redir('login');
            Session::delete("erro");
        }
    }
}
