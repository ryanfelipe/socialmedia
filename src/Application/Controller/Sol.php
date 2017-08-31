<?php
namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Sol extends Controller {
    public function __construct() {
        parent::__construct();
        if (!Session::get("logado")) {
            Session::destroy();
            Common::redir('login');
        } else if (Session::get("logado")) {
            $this->loadModel('Application\Model\Convite', 'convite');
            $this->loadModel('Application\Model\Usuario', 'usuario');
        } else {
            
        }
    }
    public function main() {
        if (Session::get("id_usuario")) {
            $this->loadView("software/socialmedia/index/sol");
        } else if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
    }    

    /**
     * Atualizar solicitação interna 
     */
    public function updateInt($codigo, $email_anfitriao) {

        $id_anfitriao = $this->usuario->getIdByEmail($email_anfitriao)["id"]; //id da pessoa que se 
        $id_convidado = Session::get("id_usuario"); // Recupera o id do usuário e torna ele do convidado       

        $this->convite->updateByCode($codigo);
        $usuario_tem_usuario = $this->gerarUsuarioUsuario($id_anfitriao, $id_convidado);

        $this->convite->cadastraAmizade($usuario_tem_usuario);
        //Session::set("convites_recebidos", "convites_recebidos");
        //$this->loadView("index/sol");
    }

    public function gerarUsuarioUsuario($id_anfitriao, $id_convidado) {//O retorno é zero uma vez que não existe id padrão na tabela
        $usuario_tem_usuario = array(
            'usuario_anfitriao' => (int) $id_anfitriao,
            'usuario_convidado' => (int) $id_convidado
        );

        return $usuario_tem_usuario;
    }
}
