<?php
/*
|---------------------------------------
|Define onde se encontra a Aplicação
|---------------------------------------
*/
define('APPLICATION_PATH', realpath(dirname(__FILE__)) . '/../src');
/*
|--------------------------------
|Define o caminho do site
|--------------------------------
*/
define('SITE_PATH', realpath(dirname(__FILE__)) . '/');
/*
|-----------------------------------------------
|Define o caminho para as views da aplicação
|-----------------------------------------------
*/
define('VIEW_PATH', APPLICATION_PATH . '/Application/View');

set_include_path(
        SITE_PATH . '../vendor' . PATH_SEPARATOR .
        APPLICATION_PATH . PATH_SEPARATOR .
        get_include_path()
);
/*
|------------------------------
|Importa a clase Autoloader
|------------------------------
 */
require_once 'Geekx/Autoloader.php';
/*
|-----------------------------
|Importa o arquivo config
|-----------------------------
*/
require_once('../config/config.php');
/*
|-----------------------------------------
|Registra as classes para serem chamadas
|-----------------------------------------
*/
try {
    $loader = new Geekx\Autoloader;
    $loader->register();    
    /*
    |------------------------------------------
    |O controlador chamado aqui é o Index
    |------------------------------------------
    */
    Geekx\Router::run(new Geekx\Request());
} catch (\Exception $e) {
    new Application\Controller\Error($e->getMessage());
}