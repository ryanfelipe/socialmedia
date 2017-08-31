<?php

namespace Application\Model;

use Geekx\Model;
use Geekx\Common;

class Usuario extends Model {

    private $tabela_usuario = "usuario";
    private $tabela_usuario_tem_usuario = "usuario_tem_usuario";

    public function getUsuarioByEmail($email) {
        $this->email = (string) $email;

        return $this->db->select("SELECT * FROM {$this->tabela_usuario} WHERE email = :email", array(':email' => $this->email), FALSE);
    }
    /*
    |-------------------------------------------------------
    |Faz a busca avançada por prefêcia de investimento
    |-------------------------------------------------------
    */
    public function searchAdvanced($preferencia, $id){
        $dados = $this->db->select("SELECT nome, email, fotoperfil FROM usuario, pref_invest WHERE usuario.id = pref_invest.usuario_id and pref_invest.preferencia1 = '{$preferencia}' and usuario.id != {$id}");

        foreach ($dados as &$value){
            $value['email'] = base64_encode($value['email']);
        }

        return $dados;
        // var_dump($dados);
        // die;
    }
    public function refMessages($id){

        return $this->db->select("SELECT nome, fotoperfil FROM {$this->tabela_usuario} WHERE id = :id", array(':id' => $id), FALSE);
    }
    public function getIdByNome($nome){
        $this->nome = (string) $nome;

        return $this->db->select("SELECT id FROM {$this->tabela_usuario} WHERE nome = :nome", array(':nome' => $this->nome), FALSE);
    }
    /*
    |----------------------------------------
    |Retorna todos os usuários cadastrados.
    |----------------------------------------
    */
    public function getAllUsers() {
        return $this->db->select("SELECT *  FROM {$this->tabela_usuario}");
    }
    public function getNumUsers(){
        return $this->db->select("SELECT count(*) numUsers  FROM {$this->tabela_usuario}");
    }
    public function getAllDados($id) {
        $this->id = (int)$id;

        return $this->db->select("SELECT nome, sobrenome, email, nascimento, minibio, logado, genero, fone, fotoperfil, fotocapa, datcad FROM {$this->tabela_usuario} WHERE id = :id", array(':id' => $this->id), FALSE);
    }
    public function getById($id) {
        $this->id = (int) $id;

        return $this->db->select("SELECT * FROM {$this->tabela_usuario} WHERE id = :id", array(':id' => $this->id), FALSE);
    }

    public function getEmailById($id) {
        $this->id = (int)$id;

        return $this->db->select("SELECT email FROM {$this->tabela_usuario} WHERE id = :id", array(':id' => $this->id), FALSE);
    }

    public function getEmailByEmail($email) {
        $this->email = (string) $email;

        return $this->db->select("SELECT email FROM {$this->tabela_usuario} WHERE email = :email", array(':email' => $this->email), FALSE);
    }

    public function getIdByEmail($email) {
        $this->email = (string) $email;

        return $this->db->select("SELECT id FROM {$this->tabela_usuario} WHERE email = :email", array(':email' => $email), FALSE);
    }
    public function getIdByEmailAndDate($email, $dateNas) {
        return $this->db->select("SELECT id FROM {$this->tabela_usuario} WHERE email = :email AND nascimento = :nascimento", array(':email' => $email, ':nascimento' => $dateNas), FALSE);
    }
    public function getIdByNasc($nascimento) {
        $this->nascimento = (string) $nascimento;

        return $this->db->select("SELECT id FROM {$this->tabela_usuario} WHERE nascimento = :nascimento", array(':nascimento' => $this->nascimento), FALSE);
    }
    public function getLike($busca) {
        $this->busca = $busca;
        return $this->db->select("SELECT * FROM {$this->tabela_usuario} WHERE nome LIKE :busca", array(':busca' => $this->busca));
    }

    public function cadastrar($dados_usuario = array()) {
        return $this->db->insert($this->tabela_usuario, $dados_usuario);
    }
    /*
    |----------------------------------------------------------
    |Atualiza os dados do usuário.
    |----------------------------------------------------------
    */
    public function atualizar($id, $usuario = array()) {
        $this->id = $id;
        $where = "id = " . (int) $this->id;

        return $this->db->update($this->tabela_usuario, $usuario, $where);
    }

    public function updateActiveTrue($id) {
        $this->id = $id;
        $where = "id = " . (int) $this->id;

        $logado = array(
            'logado' => 1
        );

        return $this->db->update($this->tabela_usuario, $logado, $where);
    }

    public function updateActiveFalse($id) {
        $this->id = $id;
        $where = "id = " . (int) $this->id;

        $logado = array(
            'logado' => 0
        );

        $this->db->update($this->tabela_usuario, $logado, $where);
    }

    public function validaUsuario($email, $senha) {
        $this->email = $email;
        $this->senha = $senha;

        if (
                Common::validarEmBranco($this->email) ||
                Common::validarEmBranco($this->senha) ||
                !Common::validarEmail($this->email)
        ) {
            return "Preencha corretamente os dados.";
        }

        $where = array(
            ':email' => $this->email,
            ':senha' => md5($this->senha),
            ':status' => true,
        );

        $encontrou = $this->db->select("SELECT id FROM {$this->tabela_usuario} WHERE email = :email AND senha = :senha AND status = :status", $where, FALSE);

        if ($encontrou) {
            return $encontrou;
        } else {
            return "Sua conta não foi encontrada no sistema.";
        }
    }

    public function validarSenhas($senha, $confirma_senha) {
        $this->senha = (int)$senha;
        $this->confirma_senha = (int)$confirma_senha;
        return Common::validarSenha($this->senha, $this->confirma_senha);
    }

    public function eRelacaoAnf($id_convidado, $id_anfitriao) {
        $this->id_convidado = (int) $id_convidado;
        $this->id_anfitriao = (int) $id_anfitriao;

        $where = array(
            ':id_convidado' => $this->id_convidado,
            ':id_anfitriao' => $this->id_anfitriao
        );

        return $this->db->select("SELECT * FROM "
                        . "usuario_tem_usuario WHERE usuario_convidado = :id_convidado and usuario_anfitriao = :id_anfitriao", $where, FALSE);
    }

    public function eRelacao($id_anfitriao, $id_convidado) {
        $this->id_anfitriao = (int) $id_anfitriao;
        $this->id_convidado = (int) $id_convidado;

        $where = array(
            ':id_anfitriao' => $this->id_anfitriao,
            ':id_convidado' => $this->id_convidado
        );

        return $this->db->select("SELECT * FROM "
                        . "usuario_tem_usuario WHERE usuario_anfitriao = :id_anfitriao and usuario_convidado = :id_convidado", $where, FALSE);
    }

    public function searchUsuario($id, $nome){
        $this->nome = $nome;
        $this->id = $id;

        $where = array(
            ':nome' => $this->nome,
            ':id' => $this->id
        );

        return $this->db->select("SELECT * FROM {$this->tabela_usuario} WHERE nome = :nome and id != :id", $where);
    }
    public function verUsuario($id_de){
        if($sao_amigos_ida || $sao_amigos__volta){
            $dados = $this->db->select("SELECT id, nome, sobrenome, email, nascimento, minibio, genero, fone, logado, fotoperfil, fotocapa, datcad FROM {$this->tabela_usuario} WHERE email = '{$email_para}'", FALSE);
            $dados_usuario = $dados[0];
            $date_cad = new \DateTime($dados_usuario['datcad']); //Esssa barr invertida no início de DateTime diz que a classe pode ser chamada externamente
            $dados_usuario['datcad'] = $date_cad->format('d/m/Y');

            $date_nas = new \DateTime($dados_usuario['nascimento']); //Esssa barr invertida no início de DateTime diz que a classe pode ser chamada externamente
            $dados_usuario['nascimento'] = $date_nas->format('d/m/Y');
            return $dados_usuario;

        }elseif(!$sao_amigos_ida || !$sao_amigos__volta){
            return $this->db->select("SELECT id, nome, sobrenome, minibio, genero, logado, fotoperfil, fotocapa, datcad FROM {$this->tabela_usuario} WHERE email = '{$email_para}'", FALSE);
        }
    }
    /*
    |------------------------------------------------------------
    |Recupera os dados do usuário quando eles são amigos
    |------------------------------------------------------------
    */
    public function verUsuarioAmigo($email_para){
        $dados = $this->db->select("SELECT id, nome, sobrenome, email, email e, nascimento, minibio, fone, logado, fotoperfil, fotocapa, datcad FROM {$this->tabela_usuario} WHERE email = '{$email_para}'", FALSE);
        $dados_usuario = $dados[0];

        $email_encode = base64_encode($dados_usuario['e']);
        $dados_usuario['e'] = $email_encode;

        $date_cad = new \DateTime($dados_usuario['datcad']); //Esssa barr invertida no início de DateTime diz que a classe pode ser chamada externamente
        $dados_usuario['datcad'] = $date_cad->format('d/m/Y');

        $date_nas = new \DateTime($dados_usuario['nascimento']); //Esssa barr invertida no início de DateTime diz que a classe pode ser chamada externamente
        $dados_usuario['nascimento'] = $date_nas->format('d/m/Y');
        return $dados_usuario;

    }
    /*
    |--------------------------------------------------------
    |Recupera os dados do usuário quando eles não são amigos
    |--------------------------------------------------------
    */
    public function verUsuarioNAmigo($email_para){
        $dados = $this->db->select("SELECT id, nome, sobrenome, email, minibio, logado, fotoperfil, fotocapa, datcad FROM {$this->tabela_usuario} WHERE email = '{$email_para}'", FALSE);
        $dados_usuario = $dados[0];
        $date_cad = new \DateTime($dados_usuario['datcad']); //Esssa barr invertida no início de DateTime diz que a classe pode ser chamada externamente
        $dados_usuario['datcad'] = $date_cad->format('d/m/Y');
        return $dados_usuario;
    }
    /*
    |-------------------------------------
    |Verifica se os usuário são amigos
    |-------------------------------------
    */
    public function verificaAmizadaIda($id_de, $id_para){
        return $sao_amigos_ida = $this->db->select("SELECT * FROM {$this->tabela_usuario_tem_usuario} WHERE usuario_anfitriao = {$id_de} and usuario_convidado = {$id_para}");
    }
    /*
    |-------------------------------------
    |Verifica se os usuário são amigos
    |-------------------------------------
    */
    public function verificaAmizadeVolta($id_de, $id_para){
        return $sao_amigos__volta = $this->db->select("SELECT * FROM {$this->tabela_usuario_tem_usuario} WHERE usuario_anfitriao = {$id_para} and usuario_convidado = {$id_de}");
    }
    /*
    |-----------------------------------------------
    |Recupera o id a partir do e-mail criptografado
    |-----------------------------------------------
    */
    public function getIdByEmailfromCript($busca){

        $email_para = base64_decode($busca);


        return $id_para = $this->getIdByEmail($email_para)['id'];
    }
    /*
    |-------------------------------------------
    |Retorna o e-mail descriptografado
    |-------------------------------------------
    */
    public function getEmailParaDecript($busca){
        $email_para = base64_decode($busca);

        return $email_para;
    }
    public function updatePass($id, $pass) {
        $where = "id = " . $id;

        $senha = array(
            'senha' => $pass
        );

        return $this->db->update($this->tabela_usuario, $senha, $where);
    }
    public function verStatus($id){
        $this->id = (int)$id;
        return $this->db->select("SELECT logado FROM {$this->tabela_usuario} WHERE id = :id", array(':id' => $this->id), FALSE)['logado'];
    }
    public function updateStatus($id, $status){
        $this->id = $id;
        $where = "id = " . (int) $this->id;

        if($status == 'online'){
            $status = 0;
        }else{
            $status = 1;
        }

        $logado = array(
            'logado' => (int)$status
        );

        return $this->db->update($this->tabela_usuario, $logado, $where);
    }
    /*
    |-----------------------------------------------------
    |Retorna os dados do usuário procurado na busca feita
    |no <<sidebar_left2.phtml>>.
    |-----------------------------------------------------
    */
    public function searchUser($id, $nome){
        $this->id = (int)$id;
        $this->nome = (string)$nome;

        $where = array(
            ':id' => $this->id,
            ':nome' => $this->nome
        );

        $dados = $this->db->select("SELECT id, nome, sobrenome, email, email e, fotoperfil FROM {$this->tabela_usuario} WHERE nome = :nome and id != :id", $where);

        foreach ($dados as &$value){
            $value['email'] = base64_encode($value['e']);
        }

        return $dados;
    }
    public function desfazerAmizade($usuario_anfitriao, $usuario_convidado){
        return $this->db->exec("DELETE FROM {$this->tabela_usuario_tem_usuario} WHERE `usuario_anfitriao` = {$usuario_anfitriao} AND `usuario_convidado` = {$usuario_convidado} OR `usuario_anfitriao` = {$usuario_convidado} AND `usuario_convidado` = {$usuario_anfitriao}");
    }
    public function updateImagePerfil($_id_usuario, $_nome_image) {
        $id_usuario = (int) $_id_usuario;
        $nome_image = (string) $_nome_image;

        $fotoperfil = array(
            'fotoperfil' => "/dist/img/into/".$nome_image
        );

        $where = "id = ".$id_usuario;

        return $this->db->update($this->tabela_usuario, $fotoperfil, $where);
    }
    public function updateImageCapa($_id_usuario, $_nome_image) {
        $id_usuario = (int) $_id_usuario;
        $nome_image = (string) $_nome_image;

        $fotocapa = array(
            'fotocapa' => $nome_image
        );

        $where = "id = ".$id_usuario;

        return $this->db->update($this->tabela_usuario, $fotocapa, $where);
    }
}
