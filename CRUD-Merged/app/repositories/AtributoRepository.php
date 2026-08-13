<?php
namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Atributo;
use PDO;

class AtributoRepository{
    private PDO $conn;


    public function __construct(){
        $this->conn = ConnectionFactory::getConnection();
    }

    public function insert($fk_tabela,$fk_atributo,$nome_atributo,$tipo,$PK,$NN,$AI,$UQ){
        $sql = "INSERT INTO atributo(fk_tabela,fk_atributo,nome_atributo,tipo,PK,NN,AI,UQ) VALUES (?,?,?,?,?,?,?,?)";
        $stm = $this->conn->prepare($sql);
        return $stm->execute([$fk_tabela,$fk_atributo,
                              $nome_atributo,$tipo,
                              $PK,$NN,$AI,$UQ]);
    }

    public function getAtributosByFk_tabela($id_tabela){
        $sql = "SELECT atributo.* FROM atributo WHERE atributo.fk_tabela = ?;";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$id_tabela]);
        return $stm->fetchAll(PDO::FETCH_NUM);
    }

    public function getAtributo($value,$param){
        $sql = "SELECT atributo.* FROM atributo WHERE ? = $param;";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$value]);
        return $stm->fetchAll(PDO::FETCH_NUM);
    }
    
    public function getAllAtributos(){
        $sql = "SELECT * from atributo";       
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_NUM); 
    }

    public function getAtributoById($id){
        $sql = "SELECT * FROM atributo WHERE ? = atributo.id_atributo;";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$id]);
        return $stm->fetch();
    }


     
    

    
}