<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class SignInAdmin extends Controller {
    public function __construct() {
        parent::__construct();
        if (Session::get("logado_admin") && Session::get("admin")) {
            Common::redir('admin');
        }
        $this->loadModel('Application\Model\Admin', 'admin');
    }
    public function main() {
        $this->loadView("software/socialmedia/sign_in_admin/index");
    }
    public function processar($admin) {
        $email = (string) filter_input(INPUT_POST, 'input-email');
        $senha = (string) filter_input(INPUT_POST, 'input-senha');
        $logar = $this->admin->validaAdmin($email, $senha);
        $id_admin = (int) $logar['id'];        

        if ($id_admin !== 0) {       
            if($admin == "admin"){
                Session::set("logado_admin", TRUE);
                Session::set("admin", TRUE);
                Session::set("id_admin", $id_admin);
                Common::redir('admin');
            }else{
                Session::delete("logado_admin");
                Session::set("erro-login-admin", "Erro ao identificar administrador!");
                Common::redir('signInAdmin');    
            }
        } else {
            Session::set("erro-login-admin", "Erro no login do administrador!");
            Common::redir('signInAdmin');
        }
    }
}
