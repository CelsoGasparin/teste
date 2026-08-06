<?php
namespace app\repositories;

use app\database\ConnectionFactory;
use PDO;


class BancoRepository{
    private PDO $conn;


    public function __construct(){
        $this->conn = ConnectionFactory::getConnection();
    }

    public function insert(){
        
    }
 
}