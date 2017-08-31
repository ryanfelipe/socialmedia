<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Chat extends Controller {
    public function __construct() {
        parent::__construct();
        if (!Session::get("logado")) {
            Session::destroy();
            Common::redir('login');
        } else {
            $this->loadModel('Application\Model\Usuario', 'usuario');
            $this->loadModel('Application\Model\Colaborador', 'colaborador');
            $this->loadModel('Application\Model\Chat', 'chat');
        }
    }
    public function main() {
        $id = Session::get("id_usuario");
        /*
        |-------------------------------------
        |Recupera os dados do usuário logado.
        |-------------------------------------
        */
        $dados_user["usuario"] = $this->usuario->getById($id);
        /*
        |--------------------------------------------
        |Recupera os dados dos amigos do usuário.
        |-------------------------------------------
        */
        $friends["amigos"] = $this->colaborador->getById($id);
        /*
        |-------------------------------------------------------
        |Passa os dados do usuário e de seus amigos para a view.
        |-------------------------------------------------------
        */
        $this->loadView("index/chat", $dados_user, $friends);
    }
    /*
    |------------------------------------------------
    |Salva uma mensagem no banco de dados.
    |------------------------------------------------
    */
    public function salvar() {
        $mensagem = (string) filter_input(INPUT_POST, 'mensagem');
        /*
        |-----------------------------------
        |ID de quem enviou a mensagem.
        |-----------------------------------
        */
        $de = (int) filter_input(INPUT_POST, 'de');
        /*
        |------------------------------------
        |ID de quem vai receber a mensagem.
        |------------------------------------
        */
        $para = (int) filter_input(INPUT_POST, 'para');
        /*
        |---------------------------------------
        |Caso a mensagem não esteja em branco
        |cria um array com os dados da conversa.
        |---------------------------------------
        */
        if ($mensagem != '') {
            $dados_conversa = array(
                'id_de' => (int) $de,
                'id_para' => (int) $para,
                'mensagem' => (string) $mensagem,
                'time' => (int) time(),
                'lido' => (int) 0
            );
            if ($this->chat->cadastrar($dados_conversa)) {
                echo 'ok';
            } else {
                echo 'no';
            }
        }
    }
    /*
    |--------------------------------------------------
    |Retorna o histórico de uma determinada conversa;
    |É invocado via ajax e retorna um json.
    |--------------------------------------------------
    */
    public function retornaHistorico() {
        $de = (int) filter_input(INPUT_POST, 'user_de');
        $para = (int) filter_input(INPUT_POST, 'user_para');
        /*
        |-------------------------------------
        |Aqui são postos os dados do dos id's
        |das pessoas envolvidas na conversa.
        |-------------------------------------
        */
        $where = array(
            ':de' => $de,
            ':para' => $para
        );
        /*
        |--------------------------
        |Retorna um arquivo json.
        |--------------------------
        */
        $mensagem = $this->chat->getHistorico($de, $where);
        die(json_encode($mensagem, JSON_UNESCAPED_UNICODE));
    }
    /*
    |-------------------------------------------------
    |Fica fazendo a verificação de mensagens novas.
    |-------------------------------------------------
    */
    public function stream() {
        /*
        |---------------------------------------------------
        |A primeira vez que esse código for executado ele
        |vai ter valor zero para timestamp e para lastid.
        |---------------------------------------------------
        */
        $userOnline = (int) filter_input(INPUT_POST, 'userOnline');
        $timestamp = (int) filter_input(INPUT_POST, 'timestamp');
        $lastid = (int) filter_input(INPUT_POST, 'lastid');
        $this->chat->getMensagens($userOnline, $timestamp, $lastid);
    }
    public function ler(){
        $ler = (string)filter_input(INPUT_POST, 'ler');
        $id_de = (int)filter_input(INPUT_POST, 'id_de');
        $id_para = (int)filter_input(INPUT_POST, 'id_para');
        die(json_encode($this->chat->ler($ler, $id_de, $id_para)));
    }
}