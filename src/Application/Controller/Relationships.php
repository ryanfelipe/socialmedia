<?php

namespace Application\Controller;

use Geekx\Controller,
    Geekx\Common,
    Geekx\Session;

class Relationships extends Controller {

    public function __construct() {
        parent::__construct();
        $this->loadModel('Application\Model\Usuario', 'usuario');
        $this->loadModel('Application\Model\Colaborador', 'colaborador');
    }

    public function main() {

        if ($id = Session::get("id_usuario")) {
            $this->loadView("software/socialmedia/index/relacoes");
        } else {
            Session::destroy();
            Common::redir('index');
        }
    }

    public function exibirPorEmail($email) {
        // Armazena o array com os dados do contato na variável que será enviada para a View
        $dados["contato"] = $this->agenda->getByid($id);

        // Esse contato realmente existe?
        if ($dados["contato"]) {
            // URL de POST para a atualização do contato
            $dados["urlAction"] = SITE_URL . "/contato/atualizar/" . $id;

            $dados["pageDesc"] = 'Visualização de "' . $dados["contato"]["nome"] . '"';
            $dados["submitDesc"] = "Salvar modificações";

            // Abre a view que exibe os contatos e passa os dados para ela
            $this->loadView("contato/index", $dados);
        } else {
            // O contato não existe, volta para a index.
            Common::redir('index');
        }
    }

    // remover

    public function remover($id) {
        if ($this->agenda->remove($id)) {
            // Cria a sessão para exibir a mensagem de remoção realizada
            Session::set("remove-ok", TRUE);
        }

        // Redireciona
        Common::redir('index');
    }

}
