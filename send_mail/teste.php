<?php
    header("content-type: text/xml");    
    $numero = $_REQUEST["numero"];
    
    if($numero % 2 == 0)
    {
        $informacao = "PAR";
    }
    else
    {
        $informacao = "ÍMPAR";
    }
    
    $dom = new DOMDocument("1.0", "UTF-8");
    $dom->preserveWhiteSpace = FALSE;
    $dom->formatOutput = TRUE;
    
    $elementoInformacao = $dom->createElement("informacao", $informacao);
    $elementoRoot = $dom->createElement("resposta");
    $elementoRoot->appendChild($elementoInformacao);
    $dom->appendChild($elementoRoot);
    echo $dom->saveXML();
?>