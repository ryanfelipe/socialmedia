<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Sociais extends Controller {
    public function __construct() {
        parent::__construct();        
        $this->loadModel('Application\Model\Sociais', 'sociais');
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
    |Retrona os links sociais do usuário.----------
    |----------------------------------------------
    */
    public function getSociais() {
        $id = Session::get("id_usuario");
        $sociais = $this->sociais->getById($id);
        die(json_encode($sociais));    
    } 
    /*
    |--------------------------------------
    |Adiciona ou edita os links sociasis.--
    |--------------------------------------
    */
    public function addOrEdit(){        
        $id = Session::get("id_usuario");  
        $bitbucket = (string) filter_input(INPUT_POST, 'bitbucket');
        $dropbox = (string) filter_input(INPUT_POST, 'dropbox');
        $facebook = (string) filter_input(INPUT_POST, 'facebook'); 
        $flickr = (string) filter_input(INPUT_POST, 'flickr'); 
        $foursquare = (string) filter_input(INPUT_POST, 'foursquare'); 
        $github = (string) filter_input(INPUT_POST, 'github'); 
        $googleplus = (string) filter_input(INPUT_POST, 'googleplus'); 
        $instagram = (string) filter_input(INPUT_POST, 'instagram'); 
        $linkedin = (string) filter_input(INPUT_POST, 'linkedin'); 
        $tumblr = (string) filter_input(INPUT_POST, 'tumblr'); 
        $twitter = (string) filter_input(INPUT_POST, 'twitter'); 
        $vk = (string) filter_input(INPUT_POST, 'vk');       
        /*
        |------------------------------------------------------
        |Tenta trazer os links sociais associados ao usuário.--
        |------------------------------------------------------
        */
        $sociais = $this->sociais->getById($id);       
        /*
        |--------------------------------
        |Se for true atualiza.----------
        |--------------------------------
        */
        if($sociais){           

            $sociais = array(
                'bitbucket' => (string)$bitbucket,
                'dropbox' => (string)$dropbox,
                'facebook' => (string)$facebook,
                'flickr' => (string)$flickr,
                'foursquare' => (string)$foursquare,
                'github' => (string)$github,
                'googleplus' => (string)$googleplus,
                'instagram' => (string)$instagram,
                'linkedin' => (string)$linkedin,
                'tumblr' => (string)$tumblr,
                'twitter' => (string)$twitter,
                'vk' => (string)$vk,
                'usuario_id' => (int)$id
            );
            $this->atualizar($sociais);
        /*
        |----------------------------------
        |Caso contrário cadastra os links.-
        |----------------------------------
        */
        }else{
            $sociais = array(
                'bitbucket' => (string)$bitbucket,
                'dropbox' => (string)$dropbox,
                'facebook' => (string)$facebook,
                'flickr' => (string)$flickr,
                'foursquare' => (string)$foursquare,
                'github' => (string)$github,
                'googleplus' => (string)$googleplus,
                'instagram' => (string)$instagram,
                'linkedin' => (string)$linkedin,
                'tumblr' => (string)$tumblr,
                'twitter' => (string)$twitter,
                'vk' => (string)$vk,
                'usuario_id' => (int)$id
            );
            $this->cadastrar($sociais);
        }
    }
    /*
    |--------------------------------------------
    |Abre a página de edição dos links sociais.--
    |--------------------------------------------
    */
    public function edit_links_sociais(){
        if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
        Session::set("editLinksSociais", "Edita os links sociais!");
        $this->loadView("index/index");
        Session::delete("editLinksSociais");
    }
    /*
    |----------------------------------------------
    |Atualiza as informações sociais.--------------
    |----------------------------------------------
    */
    public function atualizar($dados = array()){
        $id = Session::get("id_usuario");
        $update_result = $this->sociais->atualizar($id, $dados);

        if (!empty($update_result)) {
            Session::set("cadastro_sucesso", "Cadastro atualizado com sucesso!");
            Common::redir("sociais/edit_links_sociais/");
        }
    }
    /*
    |---------------------------------------
    |Cadastra as informações sociais.-------
    |---------------------------------------
    */
    public function cadastrar($dados = array()){        
        $retorno = $this->sociais->cadastrar($dados);

        if (!empty($retorno)) {
            Session::set("cadastro_sucesso", "Cadastro realizado com sucesso!");
            Common::redir("sociais/edit_links_sociais/");
        }
    }
}