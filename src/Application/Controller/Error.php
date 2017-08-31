<?php

/**
 * Controlador responsável por exibir possíveis erros
 */

namespace Application\Controller;

use Geekx\Controller;

class Error extends Controller {

    public function __construct($mensagem = "") {
        // Define a variável 'erro' que será 'enviada' para a View
        $data["erro"] = $mensagem;

        // Abre a view erro e envia para ela a mensagem do erro
        $this->loadView('error/erro', $data);
    }

}
