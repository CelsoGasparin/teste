<?php
namespace app\repositories;

use app\database\ConnectionFactory;
use PDO;

class AtributoRepository{
    private PDO $conn;


    public function __construct(){
        $this->conn = ConnectionFactory::getConnection();
    }

    public function insert(){
        $sql = "INSERT INTO atributo(fk_tabela,fk_atributo,nome_atributo,tipo,PK,NN,AI,UQ) VALUES (?,?,?,?,?,?,?,?)";
        $stm = $this->conn->prepare($sql);

        return $stm->execute([]);
    }

    public function getAtributosByFk_tabela($id_tabela){
        $sql = "SELECT atributo.* FROM atributo WHERE atributo.fk_tabela";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$id_tabela]);
        return $stm->fetchAll(PDO::FETCH_NUM);
    }
    public function getAtributos($nomeTabela){
        $validTable = preg_match('/^[a-zA-Z0-9_]+$/',$nomeTabela) ? $nomeTabela : die('Invalid table name');
        $sql = "show columns from `$validTable`";
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        
    }

    private static function map($atributos){
        $results = [];

        foreach($atributos as $key=>$atributo){
            


        } 
    }
}