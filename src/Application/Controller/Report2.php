<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Report2 extends Controller {
    public function __construct() {
        parent::__construct();
        $this->loadModel('Application\Model\Report', 'report');        
    }
    public function main() {
        if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
    }
    public function reportBug() {
                 
        $bug = (string)filter_input(INPUT_POST, 'mensagem');  
        $id = (int)Session::get("id_usuario");

        $bug = array(
            'bug' => $bug,
            'sugestao' => '',
            'usuario_id' => $id            
        );

        $success = $this->report->insertBug($bug);

        if($success){
            echo 'ok';
        }else{
            echo 'erro';
        }        
    }
}