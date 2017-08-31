<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Home extends Controller {
    public function __construct() {
        parent::__construct();
        
        if (Session::get("logado")) {
            Common::redir('index');
        }
        
    }
    public function main() {
        $this->loadView("homes/home_second/index");
    }
}
