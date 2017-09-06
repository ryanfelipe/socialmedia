<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;
    
class Admin extends Controller {
    public function __construct() {
        parent::__construct();
        if (!Session::get("logado_admin") && !Session::get("admin")) {
            Common::redir('signInAdmin');
        } else if (Session::get("logado_admin") && Session::get("admin")) {
            $this->loadModel('Application\Model\Admin', 'admin');
            $this->loadModel('Application\Model\Usuario', 'usuario');
        /*
        |-----------------------------------------------------
        |Caso aconteça algo inesperado, a sessão é destruída.
        |-----------------------------------------------------
        */
        } else {
            Session::destroy();
            Common::redir('signInAdmin');
        }
    }
    public function main() {
        $id = (int)Session::get('id_admin');
        $admin['admin'] = $this->admin->getById($id);
        $dados['usuarios'] = $this->usuario->getAllUsers();
        $this->loadView('software/socialmedia/admin/index', $dados, $admin);
    }
    public function logout() {
        Session::delete("logado_admin");
        Session::delete("admin");
        Common::redir('signInAdmin');
    }
}
