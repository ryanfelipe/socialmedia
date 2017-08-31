<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class PrefInvest extends Controller {
    public function __construct() {
        parent::__construct();
        if (Session::get("logado")) {
          $this->loadModel('Application\Model\PrefInvest', 'prefinvest');
        }else{
          Session::destroy();
          Common::redir('login');
        }
    }
    public function main() {
    }
    public function searchAdvanced(){
      $search_advanced = (int) filter_input(INPUT_POST, 'search_advanced');
      echo ($search_advanced);
    }
}
