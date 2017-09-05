<?php

namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Usuario extends Controller {

    public function __construct() {
        parent::__construct();
        $this->loadModel('Application\Model\Usuario', 'usuario');
        $this->loadModel('Application\Model\Convite', 'convite');
        $this->loadModel('Application\Model\Colaborador', 'colaborador');
        $this->loadModel('Application\Model\Invest', 'invest');
    }

    public function main() {
        if (Session::get("id_usuario")) {
            Common::redir('index');
        } else if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
    }

    public function searchAdvanced() {
        $id = Session::get("id_usuario");
        $search_advanced = (string) filter_input(INPUT_POST, 'search_advanced');
        $result['userData'] = $this->usuario->searchAdvanced($search_advanced, $id);
        Session::set('listUsersAdv', TRUE);
        $this->loadView('index/index', $result);
    }

    public function refMessages() {
        $id_de = (int) filter_input(INPUT_POST, 'id_de');
        die(json_encode($this->usuario->refMessages($id_de)));
    }

    public function userService() {
        header('Access-Control-Allow-Origin: *');
        return die(json_encode($this->usuario->getById(1)));
    }

    //Retrona os amigos do usuário em formato JSON.
    public function retornaAmigos() {
        $id = Session::get("id_usuario");
        $num = $this->colaborador->getNumAmigos($id);
        die(json_encode($num[0]));
    }

    //Carrega dos dados do usuário em formato JSON para serem usados nos mais diveros locais da interface principal.
    public function carregaDadosUsuario() {
        $id = Session::get("id_usuario");
        $dados[0] = $this->usuario->getAllDados($id);
        $dados_usuario = $dados[0];
        
        if ($dados_usuario['genero'] == 2) {
            $dados_usuario['genero'] = "Masculino";
        } else {
            $dados_usuario['genero'] = "Feminio";
        }
        
        if ($dados_usuario['fone'] == "null") {
            $dados_usuario['fone'] = "";
        }
        
        if ($dados_usuario['minibio'] == null) {
            $dados_usuario['minibio'] = "Conte um pouco sobre você..";
        }
        
        //Esssa barra(\) invertida no início de DateTime diz que a classe pode ser chamada externamente.
        $date_cad = new \DateTime($dados_usuario['datcad']);
        $dados_usuario['datcad'] = $date_cad->format('d/m/Y');
        $date_nas = new \DateTime($dados_usuario['nascimento']);
        $dados_usuario['nascimento'] = $date_nas->format('d/m/Y');
        $url = STATIC_URL_INTO . $dados_usuario['fotoperfil'];
        $dados_usuario['fotoperfil'] = $url;
        die(json_encode($dados_usuario));
    }

    //Verifica se o id do usuário está na sessão, se estiver redireciona para a página de edição.
    public function edit() {
        if (!Session::get("id_usuario")) {
            Session::destroy();
            Common::redir('index');
        }
        
        //É posto na sessão para identificar na página index que a edição precisa ser feita.
        Session::set("editUser", "Edita os dados do usuário!");
        $this->loadView("software/socialmedia/index/index");
        //Retira a identificação da sessão.
        Session::delete("editUser");
    }

    //Atualiza os dados do usuário.
    public function atualizar() {
        $id = Session::get("id_usuario");
        $nome = (string) filter_input(INPUT_POST, 'nome');
        $sobrenome = (string) filter_input(INPUT_POST, 'sobrenome');
        $email = (string) filter_input(INPUT_POST, 'email');
        $minibio = (string) filter_input(INPUT_POST, 'minibio');
        $fone = (string) filter_input(INPUT_POST, 'fone');
        /*
          |-----------------------------------------------
          |Armazena os dados em um array.
          |-----------------------------------------------
         */
        $usuario = array(
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'email' => $email,
            'minibio' => $minibio,
            'fone' => $fone
        );
        /*
          |--------------------------------------------------------------
          |Chama um método para validar os dados antes de atualizar eles.
          |--------------------------------------------------------------
         */
        $usuario_review = $this->test_input_post_atualizar($usuario);
        $update_result = $this->usuario->atualizar($id, $usuario_review);

        if (!empty($update_result)) {
            Session::set("cadastro_sucesso", "Cadastro atualizado com sucesso!");
            Common::redir("usuario/edit/");
        }
    }

    /*
      |--------------------------------------------------------------
      |Cadastra um novo usuário no sistema.
      |--------------------------------------------------------------
     */

    public function cadastrar() {
        $nome = (string) filter_input(INPUT_POST, 'nome');
        $sobrenome = (string) filter_input(INPUT_POST, 'sobrenome');
        $email_passado = (string) filter_input(INPUT_POST, 'email');
        $senha = (string) filter_input(INPUT_POST, 'senha');
        $senha_segura = md5($senha);
        $confirma_senha = (string) filter_input(INPUT_POST, 'confirma_senha');
        $genero = filter_input(INPUT_POST, 'inlineRadioOptions');
        $dia = (string) filter_input(INPUT_POST, 'dia');
        $mes = (string) filter_input(INPUT_POST, 'mes');
        $ano = (int) filter_input(INPUT_POST, 'ano');
        /*
          |--------------------------------
          |Preferências de investimento.
          |--------------------------------
         */
        $pref1 = (string) filter_input(INPUT_POST, 'optionsRadiosP1');
        /*
          |-------------------------------------------
          |Verifica o valor da preferência 1.
          |-------------------------------------------
         */
        if ($pref1 == "") {
            $pref1 = 'Não informado';
        }
        $pref2 = (string) filter_input(INPUT_POST, 'optionsRadiosP2');
        /*
          |-------------------------------------------
          |Verifica o valor da preferência 2.
          |-------------------------------------------
         */
        if ($pref2 == "") {
            $pref2 = 'Não informado';
        }
        $pref3 = (string) filter_input(INPUT_POST, 'optionsRadiosP3');
        /*
          |-------------------------------------------
          |Verifica o valor da preferência 3.
          |-------------------------------------------
         */
        if ($pref3 == "") {
            $pref3 = "Não informado";
        }
        $this->gravarDefinitivamente($nome, $sobrenome, $email_passado, $senha, $senha_segura, $confirma_senha, $genero, $dia, $mes, $ano, $pref1, $pref2, $pref3);
    }

    /*
      |------------------------------------------------------------
      |Responsável por verificar e gravar definitivamente os dados
      |do usuário no sistema.
      |------------------------------------------------------------
     */

    public function gravarDefinitivamente($nome, $sobrenome, $email_passado, $senha, $senha_segura, $confirma_senha, $genero, $dia, $mes, $ano, $pref1, $pref2, $pref3) {

        // echo $pref1.'<br/>';
        // echo $pref2.'<br/>';
        // echo $pref3.'<br/>';
        // echo $email_passado;
        // die;
        /*
          |---------------------------------------------------------
          |Verifica se existe um convite para o usuário no sistema.
          |---------------------------------------------------------
         */
        $convite = $this->verificaConviteCad($email_passado);
        /*
          |-----------------------------------
          |Verifica se as senhas são iguais.
          |-----------------------------------
         */
        $this->verificarSenhas($senha, $confirma_senha);
        /*
          |--------------------------------------------
          | Verifica o ano de nascimento do usuário.
          |--------------------------------------------
         */
        $nascimento = $this->verificarAno($ano, $mes, $dia);
        /*
          |-------------------------------------------------------------------------------------------
          |Gera um array com os dados do usuário passados para que os mesmos possam ser cadastrados.--
          |-------------------------------------------------------------------------------------------
         */
        $usuario = $this->gerarUsuario($nome, $sobrenome, $email_passado, $nascimento, $genero, $senha_segura);
        /*
          |-----------------------------------------------------------------
          |Verifica se já existe o e-maiil passado, cadastrado no sistema.--
          |-----------------------------------------------------------------
         */
        $this->verificarEmail($email_passado);
        /*
          |************************************************************************************
          |Cadastra o usuário e retorna o id.**************************************************
          |************************************************************************************
         */
        $retorno = $this->usuario->cadastrar($usuario);
        /*
          |---------------------------------------------------
          |Verifica se o usuário foi cadastrado com sucesso.--
          |---------------------------------------------------
         */
        $this->verificarCadastroUsuario($retorno);
        /*
          |--------------------------------------------------------
          |Retorna o id da pessoa que se cadastrou na linha acima.
          |--------------------------------------------------------
         */
        $id_convidado = $this->usuario->getIdByEmail($email_passado);
        /*
          |--------------------------------------------------
          |Arrays com as preferências de investimento.-------
          |--------------------------------------------------
         */
        $preferencias = $this->gerarPrefInvest($pref1, $pref2, $pref3, $id_convidado["id"]);
        $this->invest->cadastrar($preferencias);
        /*
          |-----------------------------------
          |Retorna o e-mail do anfitrião.
          |------------------------------------
         */
        $email_anf = $convite["email_anfitriao"];
        /*
          |-------------------------------------
          |Retorna o id do anfitrião.
          |-------------------------------------
         */
        $id_anfitriao = $this->usuario->getIdByEmail($email_anf);
        /*
          |------------------------------------------------------------------------------
          |Prepara esses dados para popular a tabela responsável pela relação de amizade.
          |------------------------------------------------------------------------------
         */
        $usuario_tem_usuario = $this->gerarUsuarioUsuario($id_anfitriao["id"], $id_convidado["id"]);
        /*
          |-------------------Gera a relação de amizade----------------
          |Cadastrar na tabela que será usada para consultar os amigos.
          |------------------------------------------------------------
         */
        $retorno2 = $this->convite->cadastraAmizade($usuario_tem_usuario);
        /*
          |------------------------------------------------------------------------------
          |Verifica se o cadastro ocorreu com sucesso.
          |------------------------------------------------------------------------------
         */
        $this->verificarCadastroAmizade($retorno2);
        /*
          |--------------------------------------------------
          |Atualiza a o status da tabela convite para true.
          |--------------------------------------------------
         */
        $retornoStatusConvite = $this->convite->atualizaStatus($email_anf, $email_passado);
        /*
          |------------------------------------------------------
          |Verifica se a atualização foi realizada com sucesso.
          |------------------------------------------------------
         */
        $this->verificarStatusConvite($retornoStatusConvite);
        /*
          |---------------------------------------------------------------------------
          |Gera os dados para que os mesmo sejam salvos na tabela usuário tem convite.
          |---------------------------------------------------------------------------
         */
        $usuario_convite = $this->gerarUsuarioConvite($id_anfitriao["id"], $id_convidado["id"], $convite["id"]);
        $retorno3 = $this->convite->cadastraUsuarioTemConvite($usuario_convite); //Realiza o cadastro
        /*
          |--------------------------------------------------
          |Verifica se o cadastro foi realizado com sucesso.
          |--------------------------------------------------
         */
        $this->verificarUsuarioTemConvite($retorno3);
        Session::set("cadastro", TRUE);
        Session::set("cadastro-class", "alert-success");
        Session::set("cadastro-msg", "Cadastro realizado com sucesso!");
        Common::redir('login');
        Session::delete("cadastro-msg");
    }

    /*
      |--------------------------------------------------
      |Se o e-mail já existir no sistema gera um erro.
      |--------------------------------------------------
     */

    public function verificarEmail($email_passado) {
        $email = $this->usuario->getEmailByEmail($email_passado);

        if ($email) {
            Session::set("cadastro", TRUE);
            Session::set("cadastro-class", "alert-error");
            Session::set("cadastro-msg", "E-mail já existe no sistema!");
            Common::redir('login');
            Session::delete("cadastro-msg");
        }
    }

    /*
      |---------------------------------
      |Verifica se as senhas são iguais.
      |---------------------------------
     */

    public function verificarSenhas($senha1, $senha2) {
        $senhas_ok = $this->usuario->validarSenhas($senha1, $senha2);

        if ($senhas_ok == FALSE) {
            Session::set("cadastro", TRUE);
            Session::set("cadastro-class", "alert-error");
            Session::set("cadastro-msg", "As senhas são diferentes, tente novamente!");
            Common::redir('login');
            Session::delete("cadastro-msg");
        }
    }

    /*
      |------------------------------------------
      |Verifica a idade do usuário.
      |------------------------------------------
     */

    public function verificarAno($ano, $mes, $dia) {
        if ($ano > 2006) {

            Session::set("cadastro", TRUE);
            Session::set("cadastro-class", "alert-error");
            Session::set("cadastro-msg", "Você precisa ter mais que 10 anos para se cadastrar!");
            Common::redir('login');
            Session::delete("cadastro-msg");
        }
        return $nascimento = $ano . '-' . $mes . '-' . $dia;
    }

    /*
      |-------------------------------------------------------------------
      |Verifica se existe um convite cadastrado no sistema para o usuário.
      |-------------------------------------------------------------------
     */

    public function verificaConviteCad($email_passado) {
        $convite = $this->convite->verificaConviteCad($email_passado);

        if (!$convite) {
            Session::set("cadastro", TRUE);
            Session::set("cadastro-class", "alert-error");
            Session::set("cadastro-msg", "Não existe um registro de convite para você!");
            Common::redir('login');
            Session::delete("cadastro-msg");
        } else {
            return $convite;
        }
    }

    /*
      |-------------------------------------------------------------------
      |Verifica se o usuário foi cadastrado com sucesso.
      |-------------------------------------------------------------------
     */

    public function verificarCadastroUsuario($retorno) {
        if (!$retorno) {
            Session::set("cadastro", TRUE);
            Session::set("cadastro-class", "alert-error");
            Session::set("cadastro-msg", "Por algum motivo não foi possível realizar o cadastro!");
            Common::redir('login');
            Session::delete("cadastro-msg");
        }
    }

    /*
      |----------------------------------------------------------
      |Verifica se a relação de amizade foi criada com sucesso.
      |----------------------------------------------------------
     */

    public function verificarCadastroAmizade($retorno2) {
        /*
          |-------------------------------------------------------------
          |O retorno é zero uma vez que não existe id padrão na tabela.
          |-------------------------------------------------------------
         */
        if ($retorno2) {
            /*
              |-----------------------------------------------------------
              |Cria os itens de sessão que detalham o sucesso do cadastro.
              |------------------------------------------------------------
             */
            Session::set("cadastro", TRUE);
            Session::set("cadastro-class", "alert-error");
            Session::set("cadastro-msg", "Erro ao gerar relação de amizade!");
            Common::redir('login');
            Session::delete("cadastro-msg");
        }
    }

    /*
      |------------------------------------------------------------
      |Verifica se o status do convite foi atualizado com sucesso.
      |------------------------------------------------------------
     */

    public function verificarStatusConvite($retornoStatusConvite) {
        /*
          |-------------------------------------------------------------
          |O retorno é zero uma vez que não existe id padrão na tabela.
          |-------------------------------------------------------------
         */
        if (!$retornoStatusConvite) {
            /*
              |-----------------------------------------------------------
              |Cria os itens de sessão que detalham o sucesso do cadastro.
              |------------------------------------------------------------
             */
            Session::set("cadastro", TRUE);
            Session::set("cadastro-class", "alert-error");
            Session::set("cadastro-msg", "Erro ao atualizar o status do convite!");
            Common::redir('login');
            Session::delete("cadastro-msg");
        }
    }

    /*
      |---------------------------------------------------------------------
      |Gera o array com o usuário que será cadastrado no sistema.
      |---------------------------------------------------------------------
     */

    public function gerarUsuario($nome, $sobrenome, $email_passado, $nascimento, $genero, $senha_segura, $minibio = Null) {//O retorno é zero uma vez que não existe id padrão na tabela
        $usuario = array(
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'email' => $email_passado,
            'nascimento' => $nascimento,
            'minibio' => $minibio,
            'genero' => $genero,
            'senha' => $senha_segura
        );
        return $usuario;
    }

    /*
      |-----------------------------------------------------
      |Gera o array que vai ser usado para cadastrar as-----
      |preferências de investimento.------------------------
      |-----------------------------------------------------
     */

    public function gerarPrefInvest($pref1, $pref2, $pref3, $id) {
        $prefs_invest = array(
            'preferencia1' => (string) $pref1,
            'preferencia2' => (string) $pref2,
            'preferencia3' => (string) $pref3,
            'usuario_id' => (int) $id
        );
        return $prefs_invest;
    }

    /*
      |------------------------------------------------------------------------------
      |Prepara esses dados para popular a tabela responsável pela relação de amizade.
      |------------------------------------------------------------------------------
     */

    public function gerarUsuarioUsuario($id_anfitriao, $id_convidado) {//O retorno é zero uma vez que não existe id padrão na tabela
        $usuario_tem_usuario = array(
            'usuario_anfitriao' => (int) $id_anfitriao,
            'usuario_convidado' => (int) $id_convidado
        );
        return $usuario_tem_usuario;
    }

    /*
      |---------------------------------------------------------------------------
      |Gera os dados para que os mesmo sejam salvos na tabela usuário tem convite.
      |---------------------------------------------------------------------------
     */

    public function gerarUsuarioConvite($_usuario_anfitriao, $_usuario_convidado, $_convite_id) {
        /*
          |------------------------------------------------------------
          |O retorno é zero uma vez que não existe id padrão na tabela.
          |------------------------------------------------------------
         */
        $usuario_tem_convite = array(
            'usuario_anfitriao' => (int) $_usuario_anfitriao,
            'usuario_convidado' => (int) $_usuario_convidado,
            'convite_id' => (int) $_convite_id
        );
        return $usuario_tem_convite;
    }

    /*
      |--------------------------------------------------
      |Verifica se o cadastro foi realizado com sucesso.
      |--------------------------------------------------
     */

    public function verificarUsuarioTemConvite($retorno3) {
        /*
          |------------------------------------------------------------
          |O retorno é zero uma vez que não existe id padrão na tabela.
          |------------------------------------------------------------
         */
        if ($retorno3) {
            /*
              |----------------------------------------------------
              |Cria as sessões que detalham o sucesso do cadastro.-
              |----------------------------------------------------
             */
            Session::set("cadastro", TRUE);
            Session::set("cadastro-class", "alert-error");
            Session::set("cadastro-msg", "Erro ao realizar relação de usuários e convite!");
            Common::redir('login');
            Session::delete("cadastro-msg");
        }
    }

    /*
      |-------------------------------------------------------
      |Faz uma verificação nos dados antes de atualizar eles.-
      |-------------------------------------------------------
     */

    public function test_input_post_atualizar($usuario) {
        foreach ($usuario as $value) {
            if (empty($usuario['nome'])) {
                Session::set("erro_update", "O campo Nome não pode estar em branco!");
                Common::redir("usuario/edit");
            }
            if (empty($usuario['sobrenome'])) {
                Session::set("erro_update", "O campo Sobrenome não pode estar em branco!");
                Common::redir("usuario/edit");
            }
            if (empty($usuario['email'])) {
                Session::set("erro_update", "O campo E-mail não pode estar em branco!");
                Common::redir("usuario/edit");
            }
            if (empty($usuario['minibio'])) {
                unset($usuario['minibio']);
            }
            if (empty($usuario['fone'])) {
                unset($usuario['fone']);
            }
        }
        return $usuario;
    }

    /*
      |--------------------------------------------------------------------
      |Usado para acionar a visualização do perfil de um usuário.----------
      |Decide que página abrir quando o usuário é ou não amigo do usuário--
      |que invocou a visualização.-----------------------------------------
      |--------------------------------------------------------------------
     */

    public function verUsuario($busca) {
        $id_de = Session::get("id_usuario");
        $id_para = $this->usuario->getIdByEmailfromCript($busca);
        $estao_relacionados_ida = $this->usuario->verificaAmizadaIda($id_de, $id_para);
        $estao_relacionados_volta = $this->usuario->verificaAmizadeVolta($id_de, $id_para);
        /*
          |---------------------------------------
          |Exibe um usuário amigo.----------------
          |---------------------------------------
         */
        if ($estao_relacionados_ida || $estao_relacionados_volta) {
            $email_para = $this->usuario->getEmailParaDecript($busca);
            $dados['usuario'] = $this->usuario->verUsuarioAmigo($email_para);
            $dados2['amigos'] = $this->colaborador->getNumAmigos($id_para);
            $this->loadView("software/socialmedia/index/usuario_my_friend", $dados, $dados2);
            /*
              |---------------------------------------
              |Exibe um usuário não amigo.------------
              |---------------------------------------
             */
        } elseif (!$estao_relacionados_ida || !$estao_relacionados_volta) {
            $email_de = $this->usuario->getEmailById($id_de)['email'];
            $email_para = $this->usuario->getEmailParaDecript($busca);
            $dados['usuario'] = $this->usuario->verUsuarioNAmigo($email_para);
            $dados2['amigos'] = $this->colaborador->getNumAmigos($id_para);
            /*
              |-------------------------------------------------------------------------------------
              |Faz uma busca no banco para verificar se existe um convite enviado para esse usuário-
              |-------------------------------------------------------------------------------------
             */
            $dados3['convite_interno'] = $this->convite->conEnvPenIntEsp($email_de, $email_para)[0];
            $this->loadView("software/socialmedia/index/usuario_not_friend", $dados, $dados2, $dados3);
        }
    }

    /*
      |-------------------------------------------------------
      |É acionado diretamente através da página de relações,--
      |serve para exibir o perfil do amigo.-------------------
      |-------------------------------------------------------
     */

    public function verAmizade($email) {
        $dados['usuario'] = $this->usuario->verUsuarioAmigo(base64_decode($email));
        /*
          |----------------------------
          |Recupera o id do amigo.-----
          |----------------------------
         */
        $id_para = $dados['usuario']['id'];
        /*
          |----------------------------------------
          |Recupera o número de amigos do amigo.---
          |----------------------------------------
         */
        $dados2['amigos'] = $this->colaborador->getNumAmigos($id_para);
        $this->loadView("software/socialmedia/index/usuario_my_friend", $dados, $dados2);
    }

    public function updatePass() {
        $senha_antiga = (string) filter_input(INPUT_POST, 'senha_antiga');
        $senha_nova = (string) filter_input(INPUT_POST, 'senha_nova');
        $confirma_senha_nova = (string) filter_input(INPUT_POST, 'confirma_senha_nova');
        $id = Session::get("id_usuario");
        $email = $this->usuario->getEmailById($id)['email'];
        $logar = $this->usuario->validaUsuario($email, $senha_antiga);
        $id_usuario = (int) $logar['id'];
        if ($id_usuario !== 0) {
            if ($senha_nova == $confirma_senha_nova) {
                $senha_nova_segura = md5($senha_nova);
                $this->usuario->updatePass($id, $senha_nova_segura);
                echo 'ok';
            }
        } else {
            echo 'senha_antiga:erro';
            die;
        }
    }

    public function verStatus() {
        $id = Session::get("id_usuario");
        $status = $this->usuario->verStatus($id);
        if ($status == '1') {
            echo 'online';
        } else {
            echo 'offline';
        }
    }

    public function updateStatus() {
        $status = (string) filter_input(INPUT_POST, 'status');
        $id = Session::get("id_usuario");
        $this->usuario->updateStatus($id, $status);
        $status = $this->usuario->verStatus($id);

        if ($status == '1') {
            echo 'online';
        } else {
            echo 'offline';
        }
    }

    /*
      |---------------------------------------------------
      |Realiza a busca de um usuário pelo nome.-----------
      |---------------------------------------------------
      |Exibe na página <<search.phtml>>, que é a página---
      |por exibir os usuários buscados.-------------------
      |---------------------------------------------------
     */

    public function searchUser($busca) {
        $nome = $busca;
        $id = (int) Session::get('id_usuario');
        /*
          |-----------------------------------------------------------------
          |Recupera os dados do usuário que se quer encontrar.--------------
          |-----------------------------------------------------------------
         */
        $dados['usuarios'] = $this->usuario->searchUser($id, $nome);
        $this->loadView('index/search', $dados);
    }

    public function desfazerAmizade() {
        $id = (int) Session::get('id_usuario');
        $id_para = (string) filter_input(INPUT_POST, 'id_para');
        $delecao = $this->usuario->desfazerAmizade($id, $id_para);
        echo ($delecao);
    }

    /*
      |----------------------------------
      |Gera uma nova senha pro usuário e-
      |envia pro email do mesmo.---------
      |----------------------------------
     */

    public function recuperarSenha() {
        $email = (string) filter_input(INPUT_POST, 'email');
        $dia = (string) filter_input(INPUT_POST, 'dia');
        $mes = (string) filter_input(INPUT_POST, 'mes');
        $ano = (string) filter_input(INPUT_POST, 'ano');
        $token = (string) filter_input(INPUT_POST, 'token');
        $senha = (string) filter_input(INPUT_POST, 'senha');
        $nova_senha = (string) filter_input(INPUT_POST, 'nova_senha');

        if ($senha !== $nova_senha) {
            $dados = array(
                'error' => 'As senhas são diferentes!'
            );
            die(json_encode($dados));
        }

        $nascimento = $ano . '-' . $mes . '-' . $dia;

        $id = $this->usuario->getIdByEmailAndDate($email, $nascimento)['id'];
        $token = $this->convite->getToken($token, $email);

        if ($id && $token) {
            $senha = md5($nova_senha);

            $return = $this->usuario->updatePass($id, $senha);

            if (!$return) {
                $dados = array(
                    'error' => 'Erro ao recuperar senha!'
                );
                die(json_encode($dados));
            }

            $myEmail = $email;
            $host = 'smtp.kinghost.net';
            $port = 587;
            $userName = 'capitalfuturo@capitalfuturo.club';
            $password = 'victor19871987';
            $from = 'capitalfuturo@capitalfuturo.club';
            $fromName = 'CapitalFuturo';
            $body = 'Senha alterada com sucesso!';
            $altBody = 'Senha alterada com sucesso!';

            $xml = simplexml_load_file("http://send_mail.capitalfuturo.club/PassMailService.php?email={$myEmail}&host={$host}&port={$port}&userName={$userName}&password={$password}&from={$from}&fromName={$fromName}&body={$body}&altBody={$altBody}");

            $dados = array(
                'success' => 'Senha alterada com sucesso!'
            );

            die(json_encode($dados));
        } else {
            $dados = array(
                'error' => 'Erro ao recuperar senha!'
            );
            die(json_encode($dados));
        }
    }

}
