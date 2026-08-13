<?php
namespace app\controllers;

use app\core\Controller;
use app\tools\SchemaInspector;

class SchemaController extends Controller{
    private SchemaInspector $schema_inspector;


    public function __construct($dsn,$user,$pass){
        $this->schema_inspector = new SchemaInspector($dsn,$user,$pass);
    }

    public function getDatabases(){
        $user   = trim($_POST['usuario']);
        $pass   = trim($_POST['senha']);
        $server = trim($_POST['servidor']);
        echo $this->schema_inspector->getDatabases('DB_OPTIONS');
    }
}