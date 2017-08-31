<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class Chat extends Model {
    private $table = "chat";
    /*
    |------------------------------------------
    |Cadastra um uma nova mensagem no chat.
    |------------------------------------------
    */
    public function cadastrar($mensagem = array()) {
        return $this->db->insert($this->table, $mensagem);
    }
    /*
    |---------------------------------------------------------
    |Retorna as mensagens que ainda não foram lidas no chat.
    |---------------------------------------------------------
    */
    public function mensagensNLidas($id_de){
        return $this->db->select("SELECT id_de FROM {$this->table} WHERE id_para = :id_de AND lido != 1 GROUP BY id_de ORDER BY `id` DESC", array(':id_de' => $id_de));
    }
    /*
    |-------------------------------------------------------------
    |Serve para ler as atualizar o status das mensagens para lido
    |-------------------------------------------------------------
    */    
    public function ler($ler, $id_de, $id_para){        
        $where = "id_de = " . (int) $id_para . " and id_para = " . (int) $id_de;

        if($ler == 'sim'){
            $status = 1;            
        }else{
            $status = 0;            
        }

        $lido = array(
            'lido' => (int)$status
        );

        return $this->db->update($this->table, $lido, $where);
    }
    /*
    |-----------------------------------------------------
    |Retorna o histórico das conversas,
    |para ser mais exato, as ultimas 10 conversas.
    |-----------------------------------------------------
    */
    public function getHistorico($de, $where = array()) {
        /*
        |-----------------------------------------
        |Armazena o histórico da conversa em uma
        |variável.
        |-----------------------------------------
        */
        $historico = $this->db->select("SELECT * FROM chat WHERE (`id_de` = :de AND `id_para` = :para) OR (`id_de` = :para AND `id_para` = :de) ORDER BY `id` DESC LIMIT 10", $where);
        /*
        |--------------------------------
        |Array que conterá as mensagens
        |no fim do tratamento.
        |--------------------------------
        */
        $mensagem = array();
        /*
        |----------------------------------------------
        |Itera sobre as linhas devolvidas do histórico.
        |----------------------------------------------
        */
        foreach ($historico as $row) {
            /*
            |----------------------------------------------------
            |Armazena a foto do usuário De(solicitante do chat).
            |----------------------------------------------------
            */
            $foto_de = '';
            $foto_para = '';

            if ($row['id_de'] == $de) {
                $nome_de = $this->db->select("SELECT `nome` FROM `usuario` WHERE `id` = '$row[id_de]'")[0]['nome'];
                $janela_de = $row['id_para'];
                $foto_user_de = $this->db->select("SELECT `fotoperfil` FROM `usuario` WHERE `id` = '$row[id_de]'");
                $foto_de = ($foto_user_de[0]['fotoperfil'] == 'null') ? 'default.jpg' : $foto_user_de[0]['fotoperfil'];
                /*
                |----------------------------------------------------------------------
                |Pego a imagem do suário que esta enviando a mensagem, no caso o para.
                |----------------------------------------------------------------------
                */
                $nome_para = $this->db->select("SELECT `nome` FROM `usuario` WHERE `id` = '$row[id_para]'")[0]['nome'];
                $foto_user_para = $this->db->select("SELECT `fotoperfil` FROM `usuario` WHERE `id` = '$row[id_para]'"); 
                $foto_para = ($foto_user_para[0]['fotoperfil'] == 'null') ? 'default.jpg' : $foto_user_para[0]['fotoperfil'];
            /*
            |------------------------------------------------------------
            |Eu sou o id_para;
            |A janela do outro usuário é igual a (de)
            |------------------------------------------------------------
            */
            } elseif ($row['id_para'] == $de) {
                $nome_de = $this->db->select("SELECT `nome` FROM `usuario` WHERE `id` = '$row[id_para]'")[0]['nome'];
                $janela_de = $row['id_de'];
                $foto_user_de = $this->db->select("SELECT `fotoperfil` FROM `usuario` WHERE `id` = '$row[id_para]'");
                $foto_de = ($foto_user_de[0]['fotoperfil'] == 'null') ? 'default.jpg' : $foto_user_de[0]['fotoperfil'];
                $nome_para = $this->db->select("SELECT `nome` FROM `usuario` WHERE `id` = '$row[id_de]'")[0]['nome'];
                $foto_user_para = $this->db->select("SELECT `fotoperfil` FROM `usuario` WHERE `id` = '$row[id_de]'");
                $foto_para = ($foto_user_para[0]['fotoperfil'] == 'null') ? 'default.jpg' : $foto_user_para[0]['fotoperfil'];                
            }

            $emotions = array(':)', ':@', '8)', ':D', ':3', ':(', ';)');

            $imgs = array(
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/nice.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/angry.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/cool.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/happy.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/ooh.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/sad.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/right.png" width="14"/>'
            );
            $msg = str_replace($emotions, $imgs, $row['mensagem']);
            /*
            |-------------------------------------------
            |Array contendo os itens da mensagem.
            |-------------------------------------------
            */
            $mensagem[] = array(
                'id' => $row['id'],
                'id_de' => $row['id_de'],
                'id_para' => $row['id_para'],
                'janela_de' => $janela_de,
                'mensagem' => $msg,
                'nome_de' => $nome_de,
                'nome_para' => $nome_para,
                'foto_de' => $foto_de,
                'foto_para' => $foto_para
            );
        }
        return $mensagem;        
    }
    /*
    |----------------------------------------------
    |Retorna as mensagens pro stream do controller.
    |----------------------------------------------
    |A primeira vez que esse método é executado o
    |timestamp e o lastid são iguais a 0(zero).
    |----------------------------------------------
    */
    public function getMensagens($userOnline, $timestamp, $lastid) {
        /*
        |-----------------------------------------------
        |ID do usuário que está online e possivelmente
        |vai invocar a conversa no chat.
        |-----------------------------------------------
        */
        $userOnline = $userOnline;
        /*
        |-----------------------------------------------------------------
        |Se o timestamp for 0 pega o tempo de uma função PHP.
        |------------------------OBSERVAÇÃO-------------------------------
        |A primeira vez que que a função é chamada o timestamp é 0(zero).
        |-----------------------------------------------------------------
        */
        if ($timestamp == 0) {
            $timestamp = time();//<=================Acontece na primeira vez que o código é executado!
        } else {
            /*
            |------------------------------------------------------------
            |Caso contrário retura os espaços da string com trim, depois
            |retira as tags HTML e PHP de uma string com strip_tags.
            |------------------------------------------------------------
            */
            $timestamp = strip_tags(trim($timestamp));
        }
        /*
        |-------------------------------------------------------
        |Serve para pegar as mensagens com o ultimo id, não é 
        |uma peça tão importante.
        |-------------------------------------------------------
        */
        if (!empty($lastid)) {
            $lastid = $lastid;//<=================Acontece na primeira vez que o código é executado e as outras também!
        /*
        |---------------------------------------------------
        |Nunca vai executar, a menos que ocorra um erro.
        |---------------------------------------------------
        */
        } else {
            $lastid = 0;
        }
        /*
        |-----------------------------------------------------------------------------
        |Se o timestamp estiver em branco ou devolver false.
        |-----------------------------------------------------------------------------
        |Só vai entrar nessa condição em caso de erro - Por exemplo queda na internet
        |-----------------------------------------------------------------------------        
        */
        if (empty($timestamp)) {
            /*
            |-------------------------------------------
            |Para o script devolvendo um arquivo json.
            |-------------------------------------------
            */
            die(json_encode(array('status' => 'erro')));
        }
        $tempoGasto = 0;
        $lastidQuery = '';
        /*
        |---------------------------OBSERVAÇÃO IMPORTANTE---------------------------------
        |Se o lastid for igual a 0, ele será entendido como em branco, ou seja vazio
        |---------------------------------------------------------------------------------
        */
        if (!empty($lastid)) {
            /*
            |--------------------------------------------------------------
            |A query abaixo fica algo como: AND `id` > 6(valor fictício).
            |--------------------------------------------------------------
            */
            $lastidQuery = ' AND `id` > ' . $lastid;
        }
        /*
        |---------------------------------------------------------------------------------------
        |Se o timestamp for zero serão devolvidas todas as mensagens que ainda não foram lidas.
        |---------------------------------------------------------------------------------------
        */
        if ($timestamp == 0) {
            $verifica = $this->db->prepare("SELECT * FROM chat WHERE `lido` = 0 ORDER BY `id` DESC");
        } else {
            /*
            |---------------------------------------------------------------------------
            |Senão trás as mensagens cujo timestamp é maior ou igual ao passado 
            |e o id é maior que ultimo id passado e as mensagens ainda não foram lidas.         
            |---------------------------------------------------------------------------
            */
            $verifica = $this->db->prepare("SELECT * FROM `chat` WHERE `time` >= $timestamp" . $lastidQuery . " AND `lido` = 0 ORDER BY `id`DESC");
        }
        $verifica->execute();
        $resultados = $verifica->rowCount();
        /*
        |---------------------------
        |Parte importante.
        |---------------------------
        */
        if ($resultados <= 0) {
            /*
            |------------------------------------------------------------
            |Fica verificando enquanto nenhum resultado for encontrado.
            |------------------------------------------------------------
            */
            while ($resultados <= 0) {
                if ($resultados <= 0) {
                    /*
                    |------MUITO IMPORTANTE PARA O REFLUXO DE VERIFICAÇÕES--------
                    |Passa 30 segundos verificando.
                    |-------------------------------------------------------------
                    |Não executa no primeiro momento até
                    |3 este completo.
                    |-------------------------------------------------------------
                    */                   
                    if ($tempoGasto >= 30) {
                        die(json_encode(array('status' => 'vazio', 'lastid' => 0, 'timestamp' => time())));
                        exit;
                    }
                    /*
                    |-------------------------------------
                    |Descansar o script por 1(um) segundo.
                    |-------------------------------------
                    */
                    sleep(1);
                    $verifica = $this->db->prepare("SELECT * FROM `chat` WHERE `time` >= $timestamp" . $lastidQuery . " AND `lido` = 0 ORDER BY `id`DESC");
                    $verifica->execute();
                    $resultados = $verifica->rowCount();
                    $tempoGasto += 1;                    
                }
            }
        }
        /*
        |--------------------------------------
        |Array que conterá as novas mensagens.
        |--------------------------------------
        */
        $novasMensagens = array();
        /*
        |--------------------------------------------
        |Caso resultados sejam obtidos, entra nesse
        |fluxo.
        |--------------------------------------------
        */
        if ($resultados >= 1) {
            /*
            |------------------------------------------------
            |Array que conterá as referências para os emojis.
            |------------------------------------------------
            */
            $emotions = array(':)', ':@', '8)', ':D', ':3', ':(', ';)');
            /*
            |-----------------------------------
            |Caminho para os emojis no sistema.
            |-----------------------------------
            */
            $imgs = array(
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/nice.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/angry.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/cool.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/happy.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/ooh.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/sad.png" width="14"/>',
                '<img src="http://localhost/mindmon$/public_html/static_into/dist/emotions/right.png" width="14"/>'
            );
            /*
            |----------------------------------------------------
            |Obtém a próxima linha do conjunto de resultados.
            |----------------------------------------------------
            */
            while ($row = $verifica->fetch()) {                
                $janela_de = 0;
                $foto_de = '';
                $foto_para = '';                

                if ($row['id_de'] == $userOnline) {
                    $nome_de = $this->db->select("SELECT `nome` FROM `usuario` WHERE `id` = '$row[id_de]'")[0]['nome'];
                    $janela_de = $row['id_para'];
                    $foto_user_de = $this->db->select("SELECT `fotoperfil` FROM `usuario` WHERE `id` = '$row[id_de]'");
                    $foto_de = ($foto_user_de[0]['fotoperfil'] == 'null') ? 'default.jpg' : $foto_user_de[0]['fotoperfil'];
                    /*
                    |----------------------------------------------------------------------
                    |Pego a imagem do suário que esta enviando a mensagem, no caso o para.
                    |----------------------------------------------------------------------
                    */
                    $nome_para = $this->db->select("SELECT `nome` FROM `usuario` WHERE `id` = '$row[id_para]'")[0]['nome'];
                    $foto_user_para = $this->db->select("SELECT `fotoperfil` FROM `usuario` WHERE `id` = '$row[id_para]'"); 
                    $foto_para = ($foto_user_para[0]['fotoperfil'] == 'null') ? 'default.jpg' : $foto_user_para[0]['fotoperfil'];
                /*
                |------------------------------------------------------------
                |Eu sou o id_para;
                |A janela do outro usuário é igual a (de)
                |------------------------------------------------------------
                */
                } elseif ($row['id_para'] == $userOnline) {
                    $nome_de = $this->db->select("SELECT `nome` FROM `usuario` WHERE `id` = '$row[id_para]'")[0]['nome'];
                    $janela_de = $row['id_de'];
                    $foto_user_de = $this->db->select("SELECT `fotoperfil` FROM `usuario` WHERE `id` = '$row[id_para]'");
                    $foto_de = ($foto_user_de[0]['fotoperfil'] == 'null') ? 'default.jpg' : $foto_user_de[0]['fotoperfil'];
                    $nome_para = $this->db->select("SELECT `nome` FROM `usuario` WHERE `id` = '$row[id_de]'")[0]['nome'];
                    $foto_user_para = $this->db->select("SELECT `fotoperfil` FROM `usuario` WHERE `id` = '$row[id_de]'");
                    $foto_para = ($foto_user_para[0]['fotoperfil'] == 'null') ? 'default.jpg' : $foto_user_para[0]['fotoperfil'];                
                }
                /*
                |--------------------------------------------------------
                |Substitui os emotions pelas imagens dentro da mensagem.
                |--------------------------------------------------------
                */
                $msg = str_replace($emotions, $imgs, $row['mensagem']);
                /*
                |---------------------------------------------
                |Array com as novas mensagens
                |---------------------------------------------
                */
                $novasMensagens[] = array(
                    'id' => $row['id'],
                    'id_de' => $row['id_de'],
                    'id_para' => $row['id_para'],
                    'janela_de' => $janela_de,
                    'mensagem' => $msg,
                    'nome_de' => $nome_de,
                    'nome_para' => $nome_para,
                    'foto_de' => $foto_de,
                    'foto_para' => $foto_para
                );
            }
        }
        /*
        |-----------------------------------------
        |Pega o ultimo índice(elemento) do array.
        |-----------------------------------------
        */
        $ultimaMsg = end($novasMensagens);
        /*
        |------------------------------------
        |Recupera o ID da ultima mensagem.
        |------------------------------------
        */
        $ultimoId = $ultimaMsg['id'];
        die(json_encode(array('status' => 'resultados', 'timestamp' => time(), 'lastid' => $ultimoId, 'dados' => $novasMensagens)));
    }

}
