<?php
namespace Geekx;

use \PDO;

class Database extends PDO {
    
    public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS) {
        // Executa o construtor da da classe pai (PDO) que inicializa a conexão
        parent::__construct($DB_TYPE . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=utf8', $DB_USER, $DB_PASS);
    }

    /**
     * Select no banco de dados.
     * @access public
     * @param String $sql Comando SQL.
     * @param Array $array Dados a serem retornados.
     * @param Boolean $all Usar fetchAll() ou apenas fetch()
     * @param Constant $fetchMode Define o tipo do retorno, por padrão, um array associativo.
     * @return Array
     */
    public function select($sql, $array = array(), $all = TRUE, $fetchMode = PDO::FETCH_ASSOC) {
        // Prepara a Query
        $sth = $this->prepare($sql);

        // Define os dados do Where, se existirem.
        foreach ($array as $key => $value) {
            // Se o tipo do dado for inteiro, usa PDO::PARAM_INT, caso contrário, PDO::PARAM_STR
            $tipo = ( is_int($value) ) ? PDO::PARAM_INT : PDO::PARAM_STR;

            // Define o dado
            $sth->bindValue("$key", $value, $tipo);
        }

        // Executa
        $sth->execute();

        // Executar fetchAll() ou fetch()?
        if ($all) {
            // Retorna a coleção de dados (array multidimensional)
            return $sth->fetchAll($fetchMode);
        } else {
            // Retorna apenas um dado
            return $sth->fetch($fetchMode);
        }
    }

    /**
     * Insere um dado no banco de dados.
     * @access public
     * @param String $table Nome da tabela.
     * @param Array $data Campos e seus respectivos valores.
     * @return Integer
     */
    public function insert($table, $data) {
        // Ordena
        ksort($data);

        // Campos e valores
        $camposNomes = implode('`, `', array_keys($data));
        $camposValores = ':' . implode(', :', array_keys($data));

        // Prepara a Query
        $sth = $this->prepare("INSERT INTO $table (`$camposNomes`) VALUES ($camposValores)");

        // Define os dados
        foreach ($data as $key => $value) {
            // Se o tipo do dado for inteiro, usa PDO::PARAM_INT, caso contrário, PDO::PARAM_STR
            $tipo = ( is_int($value) ) ? PDO::PARAM_INT : PDO::PARAM_STR;

            // Define o dado
            $sth->bindValue(":$key", $value, $tipo);
        }

        // Executa
        $sth->execute();

        // Retorna o ID desse item inserido
        return $this->lastInsertId();
    }

    /**
     * Atualiza um dado no banco de dados.
     * @access public
     * @param String $table Nome da tabela.
     * @param Array $data Campos e seus respectivos valores.
     * @param String $where Condição de atualização.
     * @return Integer
     */
    public function update($table, $data, $where) {
        // Ordena
        ksort($data);

        // Define os dados que serão atualizados
        $novosDados = NULL;

        foreach ($data as $key => $value) {
            $novosDados .= "`$key`=:$key,";
        }

        $novosDados = rtrim($novosDados, ',');

        // Prepara a Query
        $sth = $this->prepare("UPDATE $table SET $novosDados WHERE $where");

        // Define os dados
        foreach ($data as $key => $value) {
            // Se o tipo do dado for inteiro, usa PDO::PARAM_INT, caso contrário, PDO::PARAM_STR
            $tipo = ( is_int($value) ) ? PDO::PARAM_INT : PDO::PARAM_STR;

            // Define o dado
            $sth->bindValue(":$key", $value, $tipo);
        }

        // Sucesso ou falha?
        return $sth->execute();
    }

    /**
     * Deleta um dado da tabela.
     * @access public
     * @param String $table Nome da tabela.
     * @param String $where Condição de atualização.
     * @param Integer $limit Limite de itens deletados por execução.
     * @return Integer
     */
    public function delete($table, $where, $limit = 1) {
        // Deleta
        return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
    }

}
