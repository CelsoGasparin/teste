<?php
namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Projeto;
use PDO;

class ProjetoRepository{
    private PDO $conn;
    
    public function __construct(){
        $this->conn =  ConnectionFactory::getConnection();
    }


    public function insert(Projeto $projeto){
        $sql = "INSERT INTO `mvc_creator`.`projeto`(id_usuario,fk_banco,fk_estilo,nome_projeto,data_criacao,status_permanencia,caminho_armazenamento,comentarios,views,ultimo_download)
         VALUES (?,?,?,?,?,?,?,?,?,?);";
        $stm = $this->conn->prepare($sql);

        return $stm->execute($this->projetoParams($projeto));
    }

    public function getById(int $id){
        $sql = "SELECT * FROM projeto WHERE id_projeto = ?;";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$id]);

        return $stm->fetch();
    }

    

    private function projetoParams(Projeto $projeto){
        
        return [$projeto->getFk_usuario() ?? 1,
        $projeto->getFk_banco() ?? 1,
        $projeto->getFk_estilo() ?? null,
        $projeto->getNome_projeto() ?? '',
        $projeto->getData_criacao() ?? 'NOW',
        60,
        $projeto->getCaminho_armazenamento() ?? 'isso/Nem/Ta/Definido',
        $projeto->getComentarios() ? 1 : 0 ,
        $projeto->getViews() ? 1 : 0,
        null];
    }


    public function getTabelasByFk_banco($id_banco){
        $sql = "SELECT tabela.* FROM tabela WHERE tabela.fk_banco = ?";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$id_banco]);
        return $stm->fetchAll(PDO::FETCH_NUM);
    }

    public function getAtributos($nomeTabela){
        $validTable = preg_match('/^[a-zA-Z0-9_]+$/',$nomeTabela) ? $nomeTabela : die('Invalid table name');
        $sql = "show columns from `$validTable`";
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        
    }

    public function getDatabases($dsn,$user,$pass){
        $specialConn = ProjetoRepository::specialConn($dsn,$user,$pass);
        $sql = "SHOW DATABASES";
        $stm = $specialConn->prepare($sql);
        $stm->execute();
        $databases = $stm->fetchAll(PDO::FETCH_ASSOC);
        $aa = "";
        foreach($databases as $database){
            $aa .= "<option>". $database['Database'] ."</option>\n";
        }
        unset($specialConn);
        // print $aa;
        return $aa;
    }



    private static function specialConn($dsn,$user,$pass){
        // print $dsn;
        $connection = new PDO($dsn, $user, $pass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}