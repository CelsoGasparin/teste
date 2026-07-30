<?php
namespace app\models;

use app\database\ConnectionFactory;
use app\services\ProjetoService;

class Estrutura{
    private $server;
    private $banco;
    private $user;
    private $pass;
    private $conn;
    private $tabelas;





    public function __construct(){
        $this->conn = new ProjetoService();
        $this->conn->getById(1);
    }
}




(new Estrutura());