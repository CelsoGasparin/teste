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

    public function getAllProjetos($string = "*"){
        $sql = "SELECT ? FROM projeto";
        $stm = $this->conn->prepare($sql);
        $stm->execute([$string]);

        return $stm->fetchAll(PDO::FETCH_NUM);
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


    public function getTabelas(){
        $sql = "SHOW TABLES";
        $stm = $this->conn->prepare($sql);
        $stm->execute([]);
        return $stm->fetchAll(PDO::FETCH_NUM);
    }

    

    public function getAtributos($nomeTabela){
        $validTable = preg_match('/^[a-zA-Z0-9_]+$/',$nomeTabela) ? $nomeTabela : die('Invalid table name');
        $sql = "show columns from `$validTable`";
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        
    }

    public function getDatabases($dsn,$user,$pass,$option){
        $specialConn = ConnectionFactory::specialConn($dsn,$user,$pass);
        $sql = "SHOW DATABASES";
        $stm = $specialConn->prepare($sql);
        $stm->execute();
        $databases = $stm->fetchAll(PDO::FETCH_ASSOC);
        switch(strtolower($option)){
            case 'db_options':
                $ops = "";
                foreach($databases as $database){
                    $ops .= "<option>". $database['Database'] ."</option>\n";
                }
                unset($specialConn);
                // print $aa;
                return $ops;
            break;
            
            case 'default':
            default:
                return $databases;
            break;
        }
    }



    
}