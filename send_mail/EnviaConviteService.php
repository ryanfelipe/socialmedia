<?php
require 'PHPMailer/PHPMailerAutoload.php';
header("content-type: text/xml; charset=utf-8");
/*
|------------------------------------------
|Parâmetros enviados pro service.
|------------------------------------------
*/
$email = $_REQUEST["email"];
$host = $_REQUEST["host"];
$port = $_REQUEST["port"];
$email = $_REQUEST["email"];
$userName = $_REQUEST["userName"];
$password = $_REQUEST["password"];
$from = $_REQUEST["from"];
$fromName = $_REQUEST["fromName"];
$body = $_REQUEST["body"];
$altBody = $_REQUEST["altBody"];
/*
|------------------------------------------
|Processa o envio do e-mail.
|------------------------------------------
*/
$Mailer = new PHPMailer();
$Mailer->IsSMTP();
$Mailer->isHTML(true);
$Mailer->Charset = 'UTF-8';
$Mailer->SMTPAuth = true;
$Mailer->Host = $host;
$Mailer->Port = $port;
$Mailer->Username = $userName;
$Mailer->Password = $password;
$Mailer->From = $from;
$Mailer->FromName = $fromName;
$Mailer->Subject = 'Convite CapitalFuturo';
$Mailer->Body = $body;
$Mailer->AltBody = $altBody;
$Mailer->AddAddress($email);
/*
|------------------------------------------------------------
|Verifica se o email foi enviado e gera um xml de resposta.
|------------------------------------------------------------
*/
if($Mailer->Send())
{
    $dom = new DOMDocument("1.0", "UTF-8");
    $dom->preserveWhiteSpace = FALSE;
    $dom->formatOutput = TRUE;
    
    $elementoInformacao = $dom->createElement("informacao", "success");
    $elementoRoot = $dom->createElement("resposta");
    $elementoRoot->appendChild($elementoInformacao);
    $dom->appendChild($elementoRoot);
    echo $dom->saveXML();
}
else
{
    $dom = new DOMDocument("1.0", "UTF-8");
    $dom->preserveWhiteSpace = FALSE;
    $dom->formatOutput = TRUE;
    
    $elementoInformacao = $dom->createElement("informacao", "error");
    $elementoRoot = $dom->createElement("resposta");
    $elementoRoot->appendChild($elementoInformacao);
    $dom->appendChild($elementoRoot);
    echo $dom->saveXML();
}
?>