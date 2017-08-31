<?php
namespace Geekx;

class Upimages {
    
    public function __construct() {
    }
    /*
    |---------------------------------------
    |Envia a imagem para o local referido.
    |---------------------------------------
    */
    public function sendImage($nome_imagem, $tamanho_imagem, $tmp, $id) {        
        $pasta = $_SERVER['DOCUMENT_ROOT']."/".IMG_URL_INTO_UP."/";
        // $pasta = $_SERVER['DOCUMENT_ROOT'].IMG_URL_INTO_UP."/";
        // $caminho_no_servidor = "/home/capitalfuturo/www/public_html/static_into";
        // echo $pasta;
        // die;
        /*
        |-----------------------------------
        |formatos de imagem permitidos.
        |-----------------------------------
        */
        $permitidos = array(".jpg",".jpeg",".png");       
        /*
        |------------------------------
        |pega a extensão do arquivo.
        |------------------------------
        */
        $ext = strtolower(strrchr($nome_imagem,"."));            
        /*
        |-----------------------------------------------------------
        |verifica se a extensão está entre as extensões permitidas.
        |-----------------------------------------------------------
        */
        if(in_array($ext,$permitidos)){
            /*
            |------------------------------
            |converte o tamanho para KB.
            |------------------------------
            */
            $tamanho = round($tamanho_imagem / 1024);
            /*
            |------------------------------
            |Se imagem for até 2MB envia.
            |------------------------------
            */
            if($tamanho < 1024){ 
                /*
                |-------------------------------
                |Nome que dará a imagem.
                |-------------------------------
                */
                $nome_atual = md5($id).$ext;                           
                /*
                |-----------------------------------------------------------
                |se enviar a foto, insere o nome da foto no banco de dados
                |-----------------------------------------------------------
                */
                if(is_uploaded_file($tmp))
                {
                    if(move_uploaded_file($tmp, $pasta.$nome_atual))
                    {
                        return $nome_atual;
                    }
                    else
                    {
                        echo "Falha ao enviar de <strong>-></strong> ".$tmp." para <strong>-></strong> ".$pasta."<br/>"." <strong>Function error:</strong> move_uploaded_file";
                        die;
                    }
                }
                else
                {
                    echo 'Falha ao mover o arquivo (permissão de acesso, caminho inválido)';
                    die;
                }
                
            }else{
                echo "A imagem deve ser de no máximo 2MB";
                die;
            }
        }else{
            echo "Somente são aceitos arquivos do tipo Imagem";
            die;
        }
    }
}
?>