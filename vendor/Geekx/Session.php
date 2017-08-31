<?php
namespace Geekx;

class Session{

    /**
    * Inicializa a sessão
    * @access public
    * @return void
    */

    public static function inicializar(){
        session_start();
        session_regenerate_id();
    }

    /**
    * Grava uma informação na sessão
    * @access public
    * @param String $key
    * @param String $value
    * @return void
    */

    public static function set($key, $value){
        $_SESSION[$key] = $value;
    }

    /**
    * Retorna um dado da sessão
    * @access public
    * @param String $key
    * @return String
    */

    public static function get($key){
        if ( isset($_SESSION[$key]) ){
            return $_SESSION[$key];
        }
    }

    /**
    * Deleta um dado da sessão
    * @access public
    * @param String $key
    * @return void
    */

    public static function delete($key){
        unset($_SESSION[$key]);
    }

    /**
    * Destrói todos os dados da sessão
    * @access public
    * @return void
    */

    public static function destroy(){
        session_destroy();
    }
}