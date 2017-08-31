<?php

/**
 * Roteador
 *
 * @author Hugo Porto
 * @access public
 */

namespace Geekx;

use Geekx\Request;

class Router {

    /**
     * Método responsável por obter o nome do controlador e do método e executá-los.
     * @access public
     * @return void
     */
    public static function run(Request $request) {
        /**
         * Obtêm os segmentos da URL a partir do objeto $request
         */
        $controlador = $request->getControlador();
        $metodo = $request->getMetodo();
        $args = (array) $request->getArgs();

        $controlador = 'Application\Controller\\' . ucfirst($controlador);

        /**
         * Instancia o controlador
         */
        $controlador = new $controlador();

        /**
         *  Verifica se é possível chamar um método dentro da classe do controlador instanciada
         */
        if (!is_callable(array($controlador, $metodo))) {
            self::error("O método na {$metodo} não foi encontrado");
            return;
        }

        /**
         * Passa os argumentos para o método
         */
        call_user_func_array(array($controlador, $metodo), $args);
    }

    // Error

    protected static function error($msg) {
        throw new \Exception($msg);
    }

}
