<?php
namespace app\repositories;

use app\database\ConnectionFactory;
use PDO;
use ValueError;

class TabelaRepository{
    private PDO $conn;


    public function __construct(){
        $this->conn = ConnectionFactory::getConnection();
    }


    public function insert($nome,$fk_banco){
        $sql = "INSERT INTO Tabela(nome_tabela,fk_banco) VALUES(?,?)";
        $stm = $this->conn->prepare($sql);
    
        return $stm->execute([$nome,$fk_banco]);
    }

    public function getTabelasByFk_banco($id_banco){
        $sql = "SELECT tabela.* FROM tabela WHERE tabela.fk_banco = ?";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$id_banco]);
        return $stm->fetchAll(PDO::FETCH_NUM);
    }

    public function getTabela($value,$param){
        $sql = "SELECT * from tabela where ? = ?";       
        $stm = $this->conn->prepare($sql);
        $stm->execute([$param,$value]);
        return $stm->fetch(PDO::FETCH_NUM); 
    }
}