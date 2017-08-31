<?php
namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class TesteColaboradorModel extends Controller {

    public function __construct() {
        parent::__construct();
        $this->loadModel('Application\Model\Colaborador', 'colaborador');
    }

    public function main() {
        echo 'Teste raiz em (TesteColaboradorModel) Ok!';
    }
    /*
    |=================================================================================
    |=================================================================================
    |=====Cada método aqui tem o mesmo nome de um método correspondente no Model======
    |=================================================================================
    |=================================================================================
    */
    /*
    |--------------------------------------------------------
    |Retorna o método getById do Model
    |--------------------------------------------------------
    */
    public function getById() {           
        $dados['amigos'] = $this->colaborador->getById(1);        

        if(empty($dados['amigos'])){
            echo 'Erro ao invocar método getById()!<br/>';
        }else{
            echo 'Método getById() executos com sucesso!<br/>';
        }
    }
    /*
    |--------------------------------------------------------
    |Testa todos os métodos ao mesmo tempo.
    |-------------------------------------------------------
    */
    public function testeFull(){
        $this->getById();
    }
}
