<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;
    
class UrlRemote extends Controller {
    public function __construct() {
        parent::__construct();
    }
    public function main() {
        
    }
    public function getURL($page) {
        $email = 'victor.porto7@gmail.com';
        $host = 'smtp.kinghost.net';
        $port = 587;
        $userName = 'capitalfuturo@capitalfuturo.club';
        $password = 'victor19871987';
        $from = 'capitalfuturo@capitalfuturo.club';
        $fromName = 'CapitalFuturo';
        $body = 'Recuperação de senha efetivada';
        $altBody = 'Recuperação de senha efetivada';
        
        $xml = simplexml_load_file("http://send_mail.capitalfuturo.club/EnviaConviteService.php?email={$email}&host={$host}&port={$port}&userName={$userName}&password={$password}&from={$from}&fromName={$fromName}&body={$body}&altBody={$altBody}");

        if(isset($xml->informacao))
        {
            if($xml->informacao == "success")
            {
                echo "E-mail enviado com sucesso!";
            }
            else if($xml->informacao == "error")
            {
                echo "Falha ao enviar e-mail";
            }
            else
            {
                echo "Retorno inválido.";
            }
        }
        else
        {
            echo "Falha na comunicação com o web service.";
        }
    }
}