<?php
namespace Application\Model;
use Geekx\Model;
use Geekx\Common;

class Convite extends Model {
    private $tabela_convite = "convite";
    private $tabela_convite_interno = "convite_interno";
    /*
    |-----------------------------------------------------------
    |Cadastra um convite enviado para um usuário não cadastrado.
    |-----------------------------------------------------------
    */
    public function cadastraConvite($convite = array()) {
        return $this->db->insert($this->tabela_convite, $convite);
    }
    /*
    |----------------------------------------------------------
    |Cadastra um convite enviado para um usuário já cadastrado.
    |-----------------------------------------------------------
    */
    public function cadastraConviteInterno($convite = array()) {
        return $this->db->insert($this->tabela_convite_interno, $convite);
    }
    /*
    |-------------------------------------------------------------
    |Cria a relação de amizade entre os usuário no momento que ele
    |se cadastra.
    |-------------------------------------------------------------
    */
    public function cadastraAmizade($ids = array()) {
        return $this->db->insert("usuario_tem_usuario", $ids);
    }
    /*
    |----------------------------------------------------------------------------
    |Gera o código do convite que será enviado quando uma solicitação for enviada
    |para um usuário não cadastrado for convidado.
    |-----------------------------------------------------------------------------
    */
    public function geraConvite($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
        /*
        |---------------------------
        |Caracteres de cada tipo.
        |---------------------------
        */
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
        /*
        |----------------------------
        |Variáveis internas.
        |----------------------------
        */
        $retorno = '';
        $caracteres = '';
        /*
        |------------------------------------------------------------
        |Agrupamos todos os caracteres que poderão ser utilizados.
        |------------------------------------------------------------
        */
        $caracteres .= $lmin;
        if ($maiusculas) {
            $caracteres .= $lmai;
        }
        if ($numeros) {
            $caracteres .= $num;
        }
        if ($simbolos) {
            $caracteres .= $simb;
        }
        /*
        |---------------------------------------------
        |Calculamos o total de caracteres possíveis.
        |---------------------------------------------
        */
        $len = strlen($caracteres);

        for ($n = 1; $n <= $tamanho; $n++) {
            /*
            |-----------------------------------------------------------------------
            |Criamos um número aleatório de 1 até $len para pegar um dos caracteres.
            |-----------------------------------------------------------------------
            */
            $rand = mt_rand(1, $len);
            /*
            |----------------------------------------------------
            |Concatenamos um dos caracteres na variável $retorno.
            |-----------------------------------------------------
            */
            $retorno .= $caracteres[$rand - 1];
        }
        return $retorno;
    }
    /*
    |--------------------------------------------
    |Atualiza o status do convite.
    |--------------------------------------------
    */
    public function atualiza($convite, $id) {
        /*
        |-----------------------------
        |Monta a cláusula Where.
        |-----------------------------
        */
        $where = "id = " . (int) $id;
        /*
        |-----------------------------
        |Executa a operação.
        |-----------------------------
        */
        return $this->db->update($this->tabela_convite, $convite, $where);
    }
    /*
    |------------------------------------------------------
    |Atualiza a o status da tabela convite para true.
    |------------------------------------------------------
    */
    public function atualizaStatus($_email_anfitriao, $_email_convidado) {
        $email_anfitriao = (string) $_email_anfitriao;
        $email_convidado = (string) $_email_convidado;

        $convite = array(
            'status' => TRUE
        );        

        $where = "email_anfitriao = " . "'" . $email_anfitriao . "'" .
                " AND email_convidado = " . "'" . $email_convidado . "'";

        return $this->db->update($this->tabela_convite, $convite, $where);
    }
    /*
    |------------------------------------------------------
    |Atualiza a o status da tabela convite para true.
    |------------------------------------------------------
    */
    public function atualizaStatusInt($_email_anfitriao, $_email_convidado) {
        $email_anfitriao = (string) $_email_anfitriao;
        $email_convidado = (string) $_email_convidado;

        $convite = array(
            'status' => TRUE
        );        

        $where = "email_anfitriao = " . "'" . $email_anfitriao . "'" .
                " AND email_convidado = " . "'" . $email_convidado . "'";

        return $this->db->update($this->tabela_convite_interno, $convite, $where);
    }   
    // public function updateByCode($_codigo) {
    //     $codigo = (string) $_codigo;
    //     $convite_interno = array(
    //         'status' => TRUE
    //     );
        
    //     $where = "convite = " . "'" . $codigo . "'";        
    //     return $this->db->update($this->tabela_convite_interno, $convite_interno, $where);
    // }
    /*
    |-------------------------------------------------------------------------------
    |Verifica se já existe um convite para o convidado referido.
    |-------------------------------------------------------------------------------
    */
    public function verificaConvite($email_anfitriao, $email_convidado) {
        $this->email_anfitriao = (string) $email_anfitriao;
        $this->email_convidado = (string) $email_convidado;

        $where = array(
            ':email_anfitriao' => $this->email_anfitriao,
            ':email_convidado' => $this->email_convidado
        );
        return $this->db->select("SELECT count(id) FROM {$this->tabela_convite} WHERE email_anfitriao = :email_anfitriao AND email_convidado = :email_convidado", $where, FALSE);
    }
    /*
    |-------------------------------------------------------------------
    |Verifica se existe um convite cadastrado no sistema para o usuário.
    |-------------------------------------------------------------------
    */
    public function verificaConviteCad($email_convidado) {
        $email = (string) $email_convidado;
        return $this->db->select("SELECT id, email_anfitriao FROM {$this->tabela_convite} WHERE email_convidado = :email", array(':email' => $email), FALSE);        
    }
    /*
    |--------------------------------------------
    |Cadastra na tabela usuario_tem_convite.-----
    |--------------------------------------------
    */
    public function cadastraUsuarioTemConvite($ids = array()) {
        return $this->db->insert("usuario_tem_convite", $ids);
    }
    /*
    |----------------------------------------------------
    |Cadastra na tabela usuario_tem_convite_interno.-----
    |----------------------------------------------------
    */
    public function cadastraUsuarioTemConviteInterno($id_quem_convidou, $id_convidado, $convite_id){

        $ids = array(
            'usuario_anfitriao' => (int) $id_quem_convidou,
            'usuario_convidado' => (int) $id_convidado,
            'convite_interno_id' => (int) $convite_id
        );
        return $this->db->insert("usuario_tem_convite_interno", $ids);
    }
    /*
    |----------------------------------------------------------------
    |Recupera os dados dos convites enviados para usuários externos.
    |----------------------------------------------------------------
    */
    public function conEnvPenExt($email_anfitriao) {
        $this->email_anfitriao = (string) $email_anfitriao;
        $status = FALSE;

        $where = array(
            ':email_anfitriao' => $this->email_anfitriao,
            ':status' => $status
        );

        return $this->db->select("SELECT * FROM {$this->tabela_convite} WHERE email_anfitriao = :email_anfitriao AND status = :status", $where);
    }
    /*
    |-------------------------------------------------
    |Retorna o número de convites externos pendentes.
    |--------------------------------------------------
    */
    public function numConEnvPenExt($email_anfitriao) {
        $this->email_anfitriao = (string) $email_anfitriao;
        $status = FALSE;

        $where = array(
            ':email_anfitriao' => $this->email_anfitriao,
            ':status' => $status
        );       
        
        return $this->db->select("SELECT count(*) enviadas FROM {$this->tabela_convite} WHERE email_anfitriao = :email_anfitriao AND status = :status", $where, FALSE);
    }
    /*
    |-------------------------------------------------
    |Retorna o número de convites internos pendentes.
    |--------------------------------------------------
    */
    public function numConEnvPenInt($email_anfitriao) {
        $this->email_anfitriao = (string) $email_anfitriao;
        $status = FALSE;

        $where = array(
            ':email_anfitriao' => $this->email_anfitriao,
            ':status' => $status
        );       
        
        return $this->db->select("SELECT count(*) enviadas FROM {$this->tabela_convite_interno} WHERE email_anfitriao = :email_anfitriao AND status = :status", $where, FALSE);
    }
    /*
    |--------------------------------------------
    |Retorna o número de convites recebidos------
    |com status FALSE.---------------------------
    |--------------------------------------------
    */
    public function numConRecebidos($email){
        $this->email = $email;
        $status = FALSE;

        $where = array(
            ':email' => $this->email,
            ':status' => $status
        );

        return $this->db->select("SELECT count(*) recebidos FROM {$this->tabela_convite_interno} WHERE email_convidado = :email AND status = :status", $where, FALSE);
    }    
    /*
    |-------------Convite Interno-------------------
    |Verifica se existe um convite interno pendente.
    |-----------------------------------------------
    */
    public function conEnvPenInt($_email_anfitriao, $_status = FALSE) {
        $email_anfitriao = (string) $_email_anfitriao;
        $status = $_status;

        $where = array(
            ':email_anfitriao' => $email_anfitriao,
            ':status' => $status
        );

        return $this->db->select("SELECT * FROM {$this->tabela_convite_interno} WHERE email_anfitriao = :email_anfitriao AND status = :status", $where);
    }
    /*
    |--------------Convite Interno------------------------------
    |Verifica se existe um convite interno pendente específico.
    |-----------------------------------------------------------
    */
    public function conEnvPenIntEsp($email_anfitriao, $email_convidado) {
        $this->email_anfitriao = (string) $email_anfitriao;
        $this->email_convidado = (string) $email_convidado;
        $status = FALSE;

        $where = array(
            ':email_anfitriao' => $this->email_anfitriao,
            ':email_convidado' => $this->email_convidado,
            ':status' => $status
        );

        return $this->db->select("SELECT * FROM {$this->tabela_convite_interno} WHERE email_anfitriao = :email_anfitriao AND email_convidado = :email_convidado AND status = :status", $where);
    }
    public function conRec($_email_anfitriao, $_status = FALSE) {

        $email_anfitriao = (string) $_email_anfitriao;
        $status = $_status;

        $where = array(
            ':email_anfitriao' => $email_anfitriao,
            ':status' => $status
        );

        return $this->db->select("SELECT * FROM {$this->tabela_convite_interno} WHERE email_convidado = :email_anfitriao AND status = :status", $where);
    }
    /*
    |------------------------------------------<-Secundário
    |Remove um convite pendente
    |------------------------------------------
    */
    public function removeConviteEx($codigo) {
        $this->codigo = (string) $codigo;
        return $this->db->delete($this->tabela_convite, "convite = '$this->codigo'");
    }
    /*
    |-----------------------------------------<-Secundário
    |Remove um convite interno pendente
    |-----------------------------------------
    */
    public function removeConviteIn($codigo) {
        $this->codigo = (string) $codigo;
        return $this->db->delete($this->tabela_convite_interno, "convite = '$this->codigo'");
    }
    /*
    |-----------------------------------------<-Secundário
    |Remove um convite recebido
    |-----------------------------------------
    */
    public function removeConviteRec($codigo) {
        $this->codigo = (string) $codigo;
        return $this->db->delete($this->tabela_convite_interno, "convite = '$this->codigo'");
    }
    public function getToken($token, $email){
        
        $c = $this->db->select("SELECT convite FROM {$this->tabela_convite} WHERE convite = :convite AND email_convidado = :email", array(':convite' => $token, ':email' => $email), FALSE)['convite'];
        $c_int = $this->db->select("SELECT convite FROM {$this->tabela_convite_interno} WHERE convite = :convite AND email_convidado = :email", array(':convite' => $token, ':email' => $email), FALSE)['convite'];

        if($c || $c_int){
            return true;
        }
    }
}