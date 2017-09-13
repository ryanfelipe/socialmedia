<?php

namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class EditDataPages extends Controller {

    public function __construct() {
        parent::__construct();
        $this->loadModel('Application\Model\EditDataPages', 'editdatapages');
    }

    public function main() {
        $this->loadView('software/testes/index');
    }

    public function login() {
        $limit = (int) filter_input(INPUT_POST, 'max');
        $offset = (int) filter_input(INPUT_POST, 'init');
        $id_usuario = Session::get('id_usuario');
        die(json_encode($this->colaborador->getByIdInJsonLimit($id_usuario, $limit, $offset)));
    }
}
