<?php

namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Maintenance extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function main() {
        
        $manutencao = 1;

        if ($manutencao) {
            $this->loadView("homes/maintenance/index");
        } else {
           //Redirecionar
        }
    }
}
