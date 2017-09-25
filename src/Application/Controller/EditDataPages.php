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
}
