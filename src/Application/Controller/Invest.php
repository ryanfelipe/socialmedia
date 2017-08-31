<?php

namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Invest extends Controller {
    public function __construct() {
        parent::__construct();        
        $this->loadModel('Application\Model\Invest', 'invest');
    }
    /*
    |-------------------------------------
    |Método principal da aplicação--------
    |-------------------------------------
    */
    public function main() {
        if (Session::get("id_usuario")) {
            Common::redir('index');
        } else if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
    }
    /*
    |----------------------------------------------
    |Retrona as preferências de Investimento.------
    |----------------------------------------------
    */
    public function getPrefs() {
        $id = Session::get("id_usuario");        
        $prefs = $this->invest->getPrefs($id);
        die(json_encode($prefs));

    } 
    public function edit_prefs(){
        if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
        Session::set("editPrefInv", "Editar preferências de investimento!");
        $this->loadView("index/index");
        Session::delete("editPrefInv");
    }
    public function atualizar(){
        $id = Session::get("id_usuario");
        $pref1 = (int) filter_input(INPUT_POST, 'pref1');
        $pref2 = (int) filter_input(INPUT_POST, 'pref2');
        $pref3 = (int) filter_input(INPUT_POST, 'pref3'); 


        if($pref1 == 0){
            $pref1 = 'Não informado';
        }else if($pref1 == 1){
            $pref1 = 'Renda Fixa';
        }else if($pref1 == 2){
            $pref1 = 'Fundos de Investimento';
        }else{
            if($pref1 == 3){
                $pref1 = 'Renda Variável';
            }            
        }
        
        if($pref2 == 0){
            $pref2 = 'Não informado';
        }else if($pref2 == 1){
            $pref2 = 'Renda Fixa';
        }else if($pref2 == 2){
            $pref2 = 'Fundos de Investimento';
        }else{
            if($pref2 == 3){
                $pref2 = 'Renda Variável';
            }            
        }
             
        if($pref3 == 0){
            $pref3 = 'Não informado';
        }else if($pref3 == 1){
            $pref3 = 'Renda Fixa';
        }else if($pref3 == 2){
            $pref3 = 'Fundos de Investimento';
        }else{
            if($pref3 == 3){
                $pref3 = 'Renda Variável';
            }            
        }

        $preferencias = array(
            'preferencia1' => (string)$pref1,
            'preferencia2' => (string)$pref2,
            'preferencia3' => (string)$pref3,
        );

        // var_dump($preferencias);
        // die;
        
        $update_result = $this->invest->atualizar($id, $preferencias);

        if (!empty($update_result)) {
            Session::set("cadastro_sucesso", "Cadastro atualizado com sucesso!");
            Common::redir("invest/edit_prefs/");
        }
    }
}