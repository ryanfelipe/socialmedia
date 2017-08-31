<?php

/**
 * Classe responsável por obter os segmentos da URL informada
 *
 * @author Hugo Porto
 * @access public
 */

namespace Geekx;

class Request {

    // Variável que representará o valor padrão para o Controlador
    private $_controlador = "index";
    // Sempre que nenhum nome de métdo for passado, esse é valor padrão que o método deverá assumir   
    private $_metodo = "main";
    // Os argumento do método deverão ser passados em formato de array
    private $_args = array();

    /**
     * Método construtor
     * @access public
     * @return void
     */
    public function __construct() {
        // Se nenhuma URL foi setada retorna false 'index'        
        if (!isset($_GET["url"])) { //Aqui ocorre um acesso direto
            return false;
            //OBS.: Tudo que acontece nessa classe gira em torno da entrada de dados acima
        }

        // Explode os segmentos da URL e os armazena em um Array         
        $segmentos = explode('/', $_GET["url"]);

        // Se o controlador foi realmente definido, retorna o nome dele
        // Caso contrário retorna o controlador com o nome de variável "index"
        //array_shift -> retira o primeiro elemento do array, no caso o que vem antes do nome do controlador
        $this->_controlador = ($c = array_shift($segmentos)) ? $c : 'index';


        // Se um método foi realmente requisitado, retorna o nome dele
        // Caso contrário retorna a variável com o valor "main"
        //array_shift -> retira o nome do controlador e recupera só o nome do método
        $this->_metodo = ($m = array_shift($segmentos)) ? $m : 'main';

        // Se argumentos adicionais foram definidos, os retorna
        // Caso contrário retorna um array vazio
        $this->_args = (isset($segmentos[0])) ? $segmentos : array();
    }

    /**
     * Retorna o nome do controlador
     * @access public
     * @return String
     */
    public function getControlador() {
        return $this->_controlador;
    }

    /**
     * Retorna o nome do método
     * @access public
     * @return String
     */
    public function getMetodo() {
        return $this->_metodo;
    }

    /**
     * Retorna os segmentos adicionais (argumentos)
     * @access public
     * @return Array
     */
    public function getArgs() {
        return $this->_args;
    }

}
