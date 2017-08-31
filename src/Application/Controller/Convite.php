<?php
namespace Application\Controller;
use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Convite extends Controller {
    public function __construct() {
        parent::__construct();
        /*
        |-------------------------------<-Secundário
        |Componentes do Model Invocados.
        |-------------------------------
        */
        $this->loadModel('Application\Model\Convite', 'convite');
        $this->loadModel('Application\Model\Usuario', 'usuario');
        $this->loadModel('Application\Model\Colaborador', 'colaborador');
    }
    /*
    |---------------------------------------------------------------------------------------<-Secundário
    |Método principal da classe. É invocado por padrão quando nenhum outro método é invocado;
    |---------------------------------------------------------------------------------------
    |Precisa ser tratado com cuidado para evitar falhas de segurança na aplicação.
    |---------------------------------------------------------------------------------------
    */
    public function main() {
        if (Session::get("id_usuario")) {
            Common::redir('sol');
        } else if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
    }
    /*
    |========================================================================================<=Primário
    |========================================================================================
    |===========================Referente aos convites externos==============================
    |========================================================================================
    |========================================================================================
    */
    /*
    |-------------------------------------Vínculo JS-----------------------------------------<-Secundário
    |Mostra as solicitações enviadas e ainda pendentes na tela principal de solicitações
    |no item de referente as solicitações enviadas para não cadastrados na rede ainda;
    |----------------------------------------------------------------------------------------
    |Devolve as informações no formato JSON e tem relação direta com o arquivo load_conv.js.
    |----------------------------------------------------------------------------------------
    */
    public function conEnvPenExtJSON() {
        $id_anfitriao = Session::get("id_usuario");
        $email_anfitriao = $this->usuario->getEmailById($id_anfitriao)['email'];
        die(json_encode($this->convite->numConEnvPenExt($email_anfitriao)));
    }
    /*
    |------------------------------------------------------------------------<-Secundário
    |Convites pendentes externos enviados - O primeiro item da página de
    |convites invoca esse item.
    |------------------------------------------------------------------------
    */
    public function conEnvPenExt() {
        $id_anfitriao = Session::get("id_usuario");
        $email_anfitriao = $this->usuario->getEmailById($id_anfitriao)['email'];
        $dados["convites"] = $this->convite->conEnvPenExt($email_anfitriao);
        Session::set("convites_externos_pendentes", "convites_externos_pendentes");
        $this->loadView("software/socialmedia/index/sol", $dados);
    }
    /*
    |----------------------------------------------------------------------------<-Secundário
    | Faz o redirecionamento para a página responsável por fazer o novo convite.
    |----------------------------------------------------------------------------
    */
    public function novoConvite() {
        Session::set("convite_page", "convite_page");
        $this->loadView("software/socialmedia/index/sol");
    }
    /*
    |-----------------------------------------------------------<-Secundário
    |Remove convite pendente enviado para usuário externo.
    |-----------------------------------------------------------
    */
    public function removeConviteEx($codigo) {
        $this->convite->removeConviteEx($codigo);
        Common::redir("software/socialmedia/convite/conEnvPenExt");
    }
    /*
    |========================================================================================<=Primário
    |========================================================================================
    |===========================Referente aos convites internos==============================
    |========================================================================================
    |========================================================================================
    */
    /*
    |-----------------------------------------------<-Secundário
    |Abre a página de convite internos pendentes.
    |-----------------------------------------------
    */
    public function conEnvPenInt() {
        $id_anfitriao = Session::get("id_usuario");
        $email_anfitriao = $this->usuario->getEmailById($id_anfitriao)["email"];
        $dados["convites"] = $this->convite->conEnvPenInt($email_anfitriao);
        Session::set("convites_internos_pendentes", "convites_internos_pendentes");
        $this->loadView("software/socialmedia/index/sol", $dados);
    }
    /*
    |------------------------------------------------------------------------------<-Secundário
    |Devolve os convites pendentes internos para o arquivo convs.js exibir na tela
    |principal de solicitações.
    |------------------------------------------------------------------------------
    */
    public function conEnvPenIntJSON() {
        $id_anfitriao = Session::get("id_usuario");
        $email_anfitriao = $this->usuario->getEmailById($id_anfitriao)['email'];
        die(json_encode($this->convite->numConEnvPenInt($email_anfitriao)));
    }
    /*
    |-------------Vínculo JS---------------------------------<-Secundário
    |Devolve um JSON com os convites recebidos;--------------
    |--------------------------------------------------------
    |É utilizado pelo arquivo convs.js. e notifications.js---
    |--------------------------------------------------------
    */
    public function Recebidos(){
        $id = Session::get("id_usuario");
        $email = $this->usuario->getEmailById($id)['email'];
        die(json_encode($this->convite->numConRecebidos($email)));
    }
    /*
    |-----------------------------------------<-Secundário
    |Abre a página de convites recebidos.-----
    |-----------------------------------------
    */
    public function conRec() {
        $id_anfitriao = Session::get("id_usuario");
        $email_anfitriao = $this->usuario->getEmailById($id_anfitriao)["email"];
        $dados["convites"] = $this->convite->conRec($email_anfitriao);
        Session::set("convites_recebidos", "convites_recebidos");
        $this->loadView("software/socialmedia/index/sol", $dados);
    }
    /*
    |------------------------------------------------------------------------------------<-Secundário
    |Remove um convite interno pendente e redireciona para a página de convites externos
    |pendentes novamente.
    |------------------------------------------------------------------------------------
    */
    public function removeConviteIn($codigo) {
        $this->convite->removeConviteIn($codigo);
        Common::redir("software/socialmedia/convite/conEnvPenInt");
    }
    /*
    |-----------------------------------------------------------------------------<-Secundário
    |Remove um convite recebido e redireciona para a página de convites recebidos
    |-----------------------------------------------------------------------------
    */
    public function removeConviteRec($codigo) {
        $this->convite->removeConviteIn($codigo);
        Common::redir("software/socialmedia/convite/conRec");
    }
    /*
    |========================================================================================<=Primário
    |========================================================================================
    |=======================Referente aos convites internos e externos=======================
    |========================================================================================
    |========================================================================================
    */
    /*
    |-----------------------------------------<-Secundário
    |Envia um nov convite para outro usuário.
    |-----------------------------------------
    */
    public function convidar() {
        $id_anfitriao = Session::get("id_usuario");
        /*
        |---------------------------------------------------------
        |Recupera o número máximo de usuários cadastrados na rede.
        |---------------------------------------------------------
        */
        $numUsers = $this->usuario->getNumUsers();
        /*
        |---------------------------------------------------------
        |verifica se o número de usuários da rede alcaçou 10 mil.
        |---------------------------------------------------------
        */
        if($numUsers[0]['numUsers'] > 10000){
            Session::set("erro_convite", "Limite máximo de usuários da rede alcançado!");
            $this->loadView('software/socialmedia/index/info_convite');
            die();
        }
        /*
        |----------------------------------------------------
        |Recupera a quantidade de amigos o usuário tem.
        |----------------------------------------------------
        */
        $num = $this->colaborador->getNumAmigos($id_anfitriao);
        /*
        |------------------------------------------------
        |Verifica a quantidade de amigos o usuário tem e
        |determina se ainda pode adicionar algum com base
        |no critério do if.
        |------------------------------------------------
        */
        if($num[0] == 140){
            Session::set("erro_convite", "Você só pode ter 140 amigos!");
            $this->loadView('software/socialmedia/index/info_convite');
            die();
        }
        $email_anfitriao = $this->usuario->getEmailById($id_anfitriao)["email"];
        $email_convidado = (string) filter_input(INPUT_POST, 'convidado');
        $convite = $this->convite->geraConvite();
        /*
        |---------------------------------------------------------------<-Secundário
        |Verifica se o usuário está enviando um convite para sí próprio.
        |----------------------------------------------------------------
        */
        if ($email_anfitriao == $email_convidado) {
            Session::set("erro_convite", "Você não pode enviar um convite para o sí próprio!");
            $this->loadView('software/socialmedia/index/info_convite');
            die();
        }
        /*
        |--------------------------------------------------------------<-Secundário
        |Armazena os dados do convite em um array.
        |--------------------------------------------------------------
        */
        $convite_dados = array(
            'email_anfitriao' => $email_anfitriao,
            'email_convidado' => $email_convidado,
            'convite' => $convite
        );
        /*
        |----------------------------------------------------------------<-Secundário
        |Recupera o id do usuário que será convidado para verificar se
        |ele já está cadastrado no sistema.
        |----------------------------------------------------------------
        */
        $id_convidado = $this->usuario->getUsuarioByEmail($email_convidado)["id"];
        /*
        |-------------------------------------------------------------<-Secundário
        |Verifica se o usuário já está registrado no sistema,
        |caso já esteja entra no if.
        |-------------------------------------------------------------
        */
        if ($id_convidado) {
            /*
            |--------------------------------------------------------<-Secundário
            |Verifica se existe uma relação de anfitrião e convidado
            |entre os dados passados seguindo a ordem.
            |--------------------------------------------------------
            */
            $primeira_verificacao = $this->usuario->eRelacao($id_anfitriao, $id_convidado);
            /*
            |------------------------------------------------------------------<-Secundário
            |Faz a mesma verificação acima, porém invertendo a ordem da busca.
            |------------------------------------------------------------------
            */
            $segunda_verificacao = $this->usuario->eRelacao($id_convidado, $id_anfitriao);
            /*
            |--------------------------------------------------------------------<-Secundário
            |Se a primeira verificação for verdadeira e a segunda for falsa
            |gera um erro dizendo que já são amigos.
            |--------------------------------------------------------------------
            */
            if ($primeira_verificacao && !$segunda_verificacao) {
                $dados_user["usuario"] = $this->usuario->getById($id_anfitriao);
                Session::set("erro_convite", "Vocês já são amigos!");
                $this->loadView('software/socialmedia/index/info_convite', $dados_user);
            /*
            |--------------------------------------------------------------------<-Secundário
            |Verifica o inverso da verificação acima e gera outro erro caso
            |a resposta seja positiva.
            |--------------------------------------------------------------------
            */
            } else if (!$primeira_verificacao && $segunda_verificacao) {
                $dados_user["usuario"] = $this->usuario->getById($id_anfitriao);
                Session::set("erro_convite", "Vocês já são amigos!");
                $this->loadView('software/socialmedia/index/info_convite', $dados_user);
            /*
            |-------------------------------------------------------<-Secundário
            |Se não houver nenhum problema com as verificações acima
            |cadastra o convite interno
            |-------------------------------------------------------
            */
            } else {
                /*
                |--------------------------------------------------------------<-Secundário
                |Cadastra o convite interno e devolve o id para ser confirmado
                |--------------------------------------------------------------
                */
                $dados_user["usuario"] = $this->usuario->getById($id_anfitriao);
                Session::set("erro_convite", "Este usuário já tem registro, tente econtrar ele através da busca!");
                $this->loadView('software/socialmedia/index/info_convite', $dados_user);
            }
        /*
        |--------------------------------------------------------------<-Secundário
        |Caso o usuário não esteja cadastrado ainda salva na tabela de
        |convites externos.
        |-------------------------------------------------------------
        */
        } else {
            $existe_convite = $this->convite->verificaConvite($email_anfitriao, $email_convidado)['count(id)'];
            /*
            |-------------------------------------------------------<-Secundário
            |Verifica se você já enviou um convite para essa pessoa,
            |se sim entra no if.
            |-------------------------------------------------------
            */
            if ($existe_convite >= 1) {
                Session::set("erro_convite", "Você já enviou um convite para essa pessoa!");
                $this->loadView('software/socialmedia/index/info_convite', $dados_user);
            /*
            |-------------------------------------------------------<-Secundário
            |Caso contrário cadastro o convite externo
            |e envia um email pro  convidado.
            |-------------------------------------------------------
            */
            } else {
                /*
                |-----------------------------------------------
                |Cadastro o convite
                |-----------------------------------------------
                */
                $this->convite->cadastraConvite($convite_dados);
                /*
                |-------------------------------------------------------
                |Usado paraverificar redirecionamento
                |deve ser retirado quando testes de envio estiverem
                |sendo realizados.
                |------------------------------------------------------
                */
                Session::set("convite_sucesso", "Seu convite foi enviado com sucesso!");
                $this->loadView('software/socialmedia/index/info_convite', $dados_user);
                /*
                |-----------------------------------------------
                |Envia o e-mail pro convidado
                |-----------------------------------------------
                */
                // $email = $email_convidado;
                // $host = 'smtp.kinghost.net';
                // $port = 587;
                // $userName = 'capitalfuturo@capitalfuturo.club';
                // $password = 'victor19871987';
                // $from = 'capitalfuturo@capitalfuturo.club';
                // $fromName = 'CapitalFuturo';
                //
                //
                // $body = 'Convite de www.capitalfuturo.club.<br/>'.
                // 'Guarde este token: '.$convite.'<br/>'.
                // 'Seja um dos primeiro a entrar para uma rede social exclusiva para investidores.';
                //
                //
                // $$altBody = 'Convite de www.capitalfuturo.club.<br/>'.
                // 'Guarde este token: '.$convite.'<br/>'.
                // 'Seja um dos primeiro a entrar para uma rede social exclusiva para investidores.';
                //
                // $xml = simplexml_load_file("http://send_mail.capitalfuturo.club/EnviaConviteService.php?email={$email}&host={$host}&port={$port}&userName={$userName}&password={$password}&from={$from}&fromName={$fromName}&body={$body}&altBody={$altBody}");
                //
                // if(isset($xml->informacao)){
                //     if($xml->informacao == "success"){
                //         /*
                //         |***********************************************************************************
                //         |************Aqui acontece o envio do email para o usuário convidado****************
                //         |***********************************************************************************
                //         */
                //         Session::set("convite_sucesso", "Seu convite foi enviado com sucesso!");
                //         $this->loadView('index/info_convite', $dados_user);
                //     }else if($xml->informacao == "error"){
                //         echo "Falha ao enviar e-mail";
                //     }else{
                //         echo "Retorno inválido.";
                //     }
                // }else{
                //     echo "Falha na comunicação com o web service.";
                // }
            }
        }
    }
    /*
    |---------------------------------------------<-Secundário
    |Vem dá página de convites recebidos, é tido
    |como aceita ao convite de amizade.
    |---------------------------------------------
    */
    public function aceitarConvite($convite, $id_convite, $email_anfitriao){
        $id_quem_convidou = $this->usuario->getIdByEmail($email_anfitriao)['id'];
        $id_convite = (int)$id_convite;
        $id_convidado = (int)Session::get("id_usuario");
        $convite = (string)$convite;
        $email_convidado = (string)$this->usuario->getEmailById($id_convidado)["email"];
        $email_anfitriao = (string)$email_anfitriao;
        /*
        |-----------------------------------------<-Secundário
        |Atualiza o status do convite
        |-----------------------------------------
        */
        $this->convite->atualizaStatusInt($email_anfitriao, $email_convidado);
        /*
        |--------------------------------------------<-Secundário
        |Salva na tabela usuário tem convite intenro
        |--------------------------------------------
        */
        $this->convite->cadastraUsuarioTemConviteInterno($id_quem_convidou, $id_convidado, $id_convite);

        $usuario_tem_usuario = array(
            'usuario_anfitriao' => (int) $id_quem_convidou,
            'usuario_convidado' => (int) $id_convidado
        );

        $this->convite->cadastraAmizade($usuario_tem_usuario);
        Common::redir("software/socialmedia/convite/conRec");
    }
}
