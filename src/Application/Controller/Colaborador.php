<?php
namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Colaborador extends Controller {

    public function __construct() {
        parent::__construct();
        $this->loadModel('Application\Model\Colaborador', 'colaborador');
    }

    public function main() {
        if (Session::get("id_usuario")) {
            Common::redir('index');

        } else {
            Session::destroy();
            Common::redir('index');
        }
    }

    //Retorna os dados de todos os colaboradores em um JSON.
    public function carregaDados() {
        $limit = (int)filter_input(INPUT_POST, 'max');
        $offset = (int)filter_input(INPUT_POST, 'init');
        $id_usuario = Session::get('id_usuario');
        die(json_encode($this->colaborador->getByIdInJsonLimit($id_usuario, $limit, $offset)));
    }

    //Usado para acionar a visualização do perfil de um colaborador.
    public function verColaborador($busca){
        $id_de = Session::get('id_usuario');
        $dados['usuario'] = $this->colaborador->verColaborador($busca, $id_de);       
        
    }
}
