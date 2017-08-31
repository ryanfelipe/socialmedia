<?php
namespace Geekx;

class Controller {

    protected $session;

    /**
     * Método construtor
     * @access public
     * @return void
     */
    public function __construct() {
        // Inicializa a sessão
        Session::inicializar();
    }

    /*
    |----------------------------------
    |Responsável por abrir uma view
    |----------------------------------
    */
    protected function loadView($nome, $vars = null, $vars2 = null, $vars3 = null) {
        /*
        |-------------------------------------------
        |Exporta os dados do Array para variáveis. 
        |Semelhante às "variáveis variáveis".
        |--------------------------------------------
        */
        if (is_array($vars) && count($vars) > 0) {
            /*
            |------------------------
            |Extrai as variáveis
            |-----------------------
            */
            extract($vars, EXTR_PREFIX_SAME, 'data');
        }
        
        if (is_array($vars2) && count($vars2) > 0) {            
            extract($vars2, EXTR_PREFIX_SAME, 'data2');
        }

        if (is_array($vars3) && count($vars3) > 0) {            
            extract($vars3, EXTR_PREFIX_SAME, 'data3');
        }

        /*
        |----------------------------------------------
        |Caminho para o respectivo arquivo dessa View
        |----------------------------------------------
        */
        $arquivo = VIEW_PATH . '/' . $nome . '.phtml';

        // O arquivo existe?
        if (!file_exists($arquivo)) {
            // Não existe, então lança uma exceção.
            $this->error("Houve um erro. Essa View {$nome} nao existe.");
            
        }

        // Inclui o arquivo
        require_once( $arquivo );
    }

    /**
     * Carrega um modelo.
     * @access public
     * @param String $nome Nome do modelo a ser carregado.
     * @param String $apelido 'Apelido' para o modelo
     * @return Void
     */
    protected function loadModel($nome, $apelido = "") {
       
        $this->$nome = new $nome();
        
        if($apelido !== ''){
            $this->$apelido =& $this->$nome;
        }
    }

    /**
     * Dispara um erro.
     * @access protected
     * @param String $msg Mensagem do erro.
     * @return Void
     */
    protected function error($msg) {
        // Dispara o erro
        throw new \Exception($msg);
    }

}
