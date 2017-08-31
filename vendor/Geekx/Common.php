<?php

/**
 * Funções comuns
 *
 * @author Victor
 * @access public
 */

namespace Geekx;

class Common {

    /**
        *  Construtor privado, classe não pode ser instanciada.
        */
    private function __construct() {
        
    }

    /**
        * Valida se o dado está em branco.
        * @access public
        * @param String $dado.
        * @return Boolean
        */
    public static function validarEmBranco($dado) {
        return empty($dado);
    }

    /**
        * Valida um e-mail.
        * @access public
        * @param String $email E-mail.
        * @return Boolean
        */
    public static function validarEmail($email) {
        return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email);
    }

    /**
        * Redireciona para uma URL.
        * @access public
        * @param String $url URL a ser requisitada.
        * @return void
        */
    public static function redir($url = "") {
        header('location: ' . SITE_URL . '/' . $url);
        exit;
    }
    
    public static function validarSenha($senha, $confirma_senha){
        
        if($senha == $confirma_senha){
            
            return TRUE;
            
        }else{
            
            return FALSE;
            
        }
    }

}
