<?php
namespace Geekx;

class Model
{
    function __construct()
    {
        // Cria na propriedade 'db' o objeto da classe Database
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }
}