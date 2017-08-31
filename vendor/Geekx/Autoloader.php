<?php
namespace Geekx;

class Autoloader {
    /**
     * Array para receber os diretórios
     * Nossa classe varre esse array em busca das classes
     */
    public $diretorios = array();

    public function register() {
        
        /**
         * Passa a própria classe com o ($this) e o método (loader)
         */
        spl_autoload_register(array($this, 'loader'));
    }

    /**
     * Faz o carregamento da classe
     * @param type $className (Classe que ele está tentando carregar)    
     * @throws Exception
     */
    private function loader($className) {

        if (strstr($className, '\\')) {
            $class = str_replace('\\', DIRECTORY_SEPARATOR, ltrim($className, '\\')) . '.php';
        } else {
            $class = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        }

        if (!empty($this->diretorios)) {
            foreach ($this->diretorios as $diretorio) {
                $classPath = rtrim($diretorio, '/') . DIRECTORY_SEPARATOR . $class;
                if (file_exists($classPath)) {
                    return include_once $classPath;
                }
            }
        }

        if (file_exists($class)) {
            return include_once $class;
        }

        $classPath = stream_resolve_include_path($class);
        if ($classPath !== false) {
            return include_once $classPath;
            
            throw new Exception("Arquivo {$class} não encontrado!");
        }
    }

}
