<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Notification extends Controller {
    public function __construct() {
        parent::__construct();
        $this->loadModel('Application\Model\Usuario', 'usuario');
        $this->loadModel('Application\Model\Convite', 'convite');
        $this->loadModel('Application\Model\Colaborador', 'colaborador');
        $this->loadModel('Application\Model\Chat', 'chat');
    }
    public function main() {
        if (Session::get("id_usuario")) {
            Common::redir('index');
        } else if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
    }
    /*
    |---------------------------------------
    |Verifica se existe convites pendentes.
    |---------------------------------------
    */
    public function verificaConvites() {
        $id = Session::get("id_usuario");
        $email = $this->usuario->getEmailById($id)['email'];
        $resultado = $this->convite->numConRecebidos($email);
        die(json_encode($resultado));        
    }
    /*
    |-----------------------------------------
    |Verifica se existem mensagens não lidas.
    |-----------------------------------------
    */
    public function verificaMensagens(){
        $id_de = Session::get("id_usuario");
        $resultado = $this->chat->mensagensNLidas($id_de);
        die(json_encode($resultado));
        // foreach($resultado as $array_message){
        //     echo($array_message['id_de']).'<br/>';                  
        // }
        // var_dump($resultado);
    }
}