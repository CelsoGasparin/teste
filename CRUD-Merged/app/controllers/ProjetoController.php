<?php
namespace app\controllers;

use app\core\Controller;
use app\helpers\Validador;
use app\models\Tabela;
use app\models\Projeto;

use app\repositories\ProjetoRepository;
use app\tools\SchemaInspector;
use app\services\ProjetoService;
use app\services\TabelaService;

class ProjetoController extends Controller{
    
    private ProjetoService $projetoService;

    public function __construct(){
        $this->projetoService = new ProjetoService();
    }


    public function getDatabases(){
        $user   = trim($_POST['user']);
        $pass   = trim($_POST['pass']);
        $server = trim($_POST['server']);
        echo (new SchemaInspector("mysql:host=$server",$user,$pass))->getDatabases('DB_OPTIONS');
    }


    public function index(): void {
        $this->view('projetos/home');
    }

    public function teste(){
        $schema = new SchemaInspector("mysql:host=127.0.0.1;dbname=mvc_creator",'root','bancodedados');
        $tabela = $schema->getTabelas();
        $tabelaService = new TabelaService();
        // print_r($tabela);
        foreach($tabela as $key => $value){
            print_r($schema->getAtributos($value[0]));
        }
    }

    public function cadastrar(): void {
        // (new ProjetoRepository())->getTabelasByFk_banco(1);
        $this->view("projetos/projeto_create");
    }

    public function editar(): void {
        
        $data = [];
        $id_projeto = $_POST['id_projeto'];
        $proj = $this->projetoService->getById($id_projeto);
        $nome = $proj['nome_projeto'];


        $data['nome'] = $nome;
        $this->view("projetos/projeto_edit",$data);

    }

    public function bools(){
        
        $validador = new Validador();
        $nome   = $_POST['nome'];
        $server = $_POST['server'];
        $user   = $_POST['user'];
        $pass   = $_POST['pass'];
        $banco  = $_POST['mvc-banco'];
        $this->createObrigatorios($validador,$nome,$server,$user,$pass,$banco);
        $data =["nome"=>$nome,"server"=>$server,"user"=>$user,"pass"=>$pass,'banco'=>$banco];
        // print_r($_POST);
        $this->view("projetos/projeto_bools",$data);
    }

    public function editBools(){
        $validador = new Validador();
        $nome = $_POST['nome'];

        $validador->obrigatorio('nome',$nome);
        if($validador->temErros())$this->view("");
    }

    public function criar(){
        // var_dump($_POST);
        $validador = new Validador();
        $nome   = trim($_POST['nome']);
        $server = trim($_POST['server']);
        $user   = trim($_POST['user']);
        $pass   = trim($_POST['pass']);
        $banco  = trim($_POST['mvc-banco']);
        $options = [];
        foreach($_POST as $key=>$value){
            if(substr($key,0,3)=='opt'){
                $options[substr($key,4)] = (int)$value;
            }
        }
        $this->createObrigatorios($validador,$nome,$server,$user,$pass,$banco);
        
        $projeto = new Projeto(1,1,null,$nome,date("Y-m-d H:i:s"),$options,null);


        $this->projetoService->insert($projeto);
        
        $this->redirect(URL_BASE);
        
        // $this->view("projetos/projeto_create");
    }

    private function createObrigatorios(Validador $validador, $nome,$server,$user,$pass,$banco){
        $validador->obrigatorio('nome',$nome);
        $validador->obrigatorio('server',$server);
        $validador->obrigatorio('user',$user);
        // $validador->obrigatorio('pass',$pass);
        $validador->obrigatorio('banco',$banco);

        if($validador->temErros()){
            $data['erros'] = $validador->getErros();
            $data['nome'] = $nome;
            $data['server'] = $server;
            $data['user'] = $user;
            $data['pass'] = $pass;
            $data['banco'] = $banco;
            $this->view('/projetos/projeto_create',$data);
            die;
        }
    }

}