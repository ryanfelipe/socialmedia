<?php
/*
|========================================================================================<=Primário
|========================================================================================
|===========================Arquivo de configurações do sistema==========================
|========================================================================================
|========================================================================================
*/
/*
|----------------------------------<-Secundário
| Configurações do banco de dados
|----------------------------------
*/
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'socialmedia');
define('DB_USER', 'root');
define('DB_PASS', '');
/*
|---------------------------------------------------<-Secundário
|Seta o caminho para URL principal do sistema
|---------------------------------------------------
*/
define('SITE_URL','http://localhost/socialmedia');
/*
|-----------------------------------------------------------------<-Secundário
|Seta o caminho para os arquivos estáticos da camada 1 do sistema
|-----------------------------------------------------------------
*/
define('STATIC_URL','http://localhost/socialmedia/public_html/static');
/*
|---------------------------------------------------------<-Secundário
|Seta o caminho para os arquivos estáticos do sistema
|---------------------------------------------------------
*/
define('STATIC_URL_INTO','http://localhost/socialmedia/public_html/static_into');
define('SEMANTIC_STATIC_INTO','http://localhost/socialmedia/public_html/semantic_static');
/*
|---------------------------------------------------------------------<-Secundário
|Seta o caminho para a pasta de imagens da primeira camada do sistema
|---------------------------------------------------------------------
*/
define('IMG_URL','http://localhost/socialmedia/public_html/static/img');
/*
|---------------------------------------------------------------------<-Secundário
|Seta o caminho para a pasta de imagens da segunda camada do sistema
|---------------------------------------------------------------------
*/
define('IMG_URL_INTO','http://localhost/socialmedia/public_html/static_into/dist/img');
/*
|---------------------------------------------------------------------<-Secundário
|Seta o caminho para a pasta de imagens da segunda camada do sistema
|Esta constante é usada pela classe Upimages para fazer uploads
|---------------------------------------------------------------------
*/
define('IMG_URL_INTO_UP','socialmedia/public_html/static_into/dist/img/into');
/*
|---------------------------------------------------------------------<-Secundário
|Seta o caminho para a pasta de imagens da segunda camada do sistema
|Esta constante é usada pela classe Upimages redimensionar as imagens.
|---------------------------------------------------------------------
*/
define('IMG_URL_INTO_REDI','http://localhost/socialmedia/public_html/static_into/dist/img/into');

define('HOME_ONE','http://localhost/socialmedia/public_html/homes/home_one');
define('HOME_SECOND','http://localhost/socialmedia/public_html/homes/home_second');
