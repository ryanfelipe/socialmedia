<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session,
    Geekx\Upimages,
    Geekx\TutsupRedimensionaImagem;

class SendImage extends Controller {
    
    public function __construct() {
        parent::__construct();      
        $this->loadModel('Application\Model\Usuario', 'usuario');
        
    }
    public function main() {
       if (Session::get("id_usuario")) {
            Common::redir('index');
        } else if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        } 
    }
    public function sendImage(){
        if(isset($_POST)){
            $id = Session::get("id_usuario");
            $nome_imagem = $_FILES['arquivo']['name'];
            $tamanho_imagem = $_FILES['arquivo']['size']; 
            $erro = $_FILES["arquivo"]["error"];
            if($erro !== 0){
                echo 'Erro ao selecionar aquivo!';
                die;
            }
            /*
            |------------------------------
            |Caminho temporário da imagem.
            |------------------------------
            */
            $tmp = $_FILES['arquivo']['tmp_name'];
            /*
            |---------------------------------------------------------------
            |Cria uma instância da classe que vai fazer o upload da imagem.
            |---------------------------------------------------------------
            */
            $up = new Upimages();
            /*
            |---------------------------------------------------------------
            |Faz o upload da imagem.
            |---------------------------------------------------------------
            */
            $nome_image = $up->sendImage($nome_imagem, $tamanho_imagem, $tmp, $id);                    
            /*
            |-------------------------------------------------------------------
            |Altera a referência da imagem padrão do usuário no banco de dados.
            |-------------------------------------------------------------------
            */
            $this->usuario->updateImagePerfil($id, $nome_image);
            /*
            |==================================================================================
            |==================================================================================
            |===Instancia a classe responsável pelo redimensionamento da imagem para 160x160===
            |==================================================================================
            |==================================================================================
            */
            //$caminho_imagem = IMG_URL_INTO_REDI."/".$nome_image;

            $caminho_imagem = $_SERVER['DOCUMENT_ROOT']."/".IMG_URL_INTO_UP."/".$nome_image;

           
            $redimensiona = new TutsupRedimensionaImagem($caminho_imagem, $caminho_imagem);
            $redimensiona->largura = 160;    
            $redimensiona->altura = 160;           
            $redimensiona->qualidade = 100;            
            /*
            |-----------------------------
            |Gera a nova imagem.
            |-----------------------------
            */
            $nova_imagem = $redimensiona->executa();            
            /*
            |----------------------------------------------------------------
            |Se não for uma imagem temporária, você poderá exibi-la assim.
            |----------------------------------------------------------------
            */
            // if ( $imagem->imagem_destino && $nova_imagem ) {
            //     echo "<img src='{$nova_imagem}'>";
            // }            
            /*
            |---------------------------------------------------------------
            |Se você quiser ver se algum erro ocorreu, utilize o seguinte.
            |---------------------------------------------------------------
            */
            if ( $redimensiona->erro ){
                echo $redimensiona->erro;
                die;
            }
            echo 'Imagem enviada com sucesso!';
            die;
        }else{
            echo "Selecione uma imagem";
            die;;
        }       
    }
    public function sendCapa(){
        if(isset($_POST)){
            $id = Session::get("id_usuario");
            $nome_imagem = $_FILES['arquivo']['name'];
            $tamanho_imagem = $_FILES['arquivo']['size'];     
            $tmp = $_FILES['arquivo']['tmp_name'];
            $erro = $_FILES["arquivo"]["error"];
            if($erro !== 0){
                echo 'Erro ao selecionar aquivo!';
                die;
            }
            /*
            |---------------------------------------------------------------
            |Cria uma instância da classe que vai fazer o upload da imagem.
            |---------------------------------------------------------------
            */
            $up = new Upimages();                        
            /*
            |-------------------------------------------------------------------------
            |O ID é modificado aqui para gera um nome diferente para a imagem de capa
            |-------------------------------------------------------------------------
            */
            $id_mod = $id.'1';   
            /*
            |---------------------------------------------------------------
            |Faz o upload da imagem.
            |---------------------------------------------------------------
            */      
            $nome_image = $up->sendImage($nome_imagem, $tamanho_imagem, $tmp, $id_mod);            
            /*
            |-------------------------------------------------------------------
            |Altera a referência da imagem padrão do usuário no banco de dados.
            |-------------------------------------------------------------------
            */
            $update = $this->usuario->updateImageCapa($id, $nome_image);           
            /*
            |==================================================================================
            |==================================================================================
            |===Instancia a classe responsável pelo redimensionamento da imagem para 160x160===
            |==================================================================================
            |==================================================================================
            */
            $caminho_imagem = $_SERVER['DOCUMENT_ROOT']."/".IMG_URL_INTO_UP."/".$nome_image;                      
            $redimensiona = new TutsupRedimensionaImagem($caminho_imagem, $caminho_imagem);
            $redimensiona->largura = 800;    
            $redimensiona->altura = 220;           
            $redimensiona->qualidade = 100;            
            /*
            |-----------------------------
            |Gera a nova imagem.
            |-----------------------------
            */
            $nova_imagem = $redimensiona->executa();            
           
            if ( $redimensiona->erro ){
                echo $redimensiona->erro;
                die;
            }
            echo 'Imagem enviada com sucesso!';
            die;
        }else{
            echo "Selecione uma imagem";
            die;
        }       
    }
}