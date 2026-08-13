<?php
namespace app\controllers;

use app\core\Controller;
use app\helpers\Validador;
use app\models\Tabela;
use app\models\Projeto;
use app\services\AtributoService;
use app\services\BancoService;
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
        $host = '127.0.0.1:3306';
        $hostEporta = explode(':',$host);
        $nome_banco = 'db_projeto_integrador';
        $usuario_banco = 'root';
        $senha_banco = '';
        $fk_usuario = $_SESSION['usuario_logado']->getIdUsuario();
        
        // SchemaInspector com o Banco Selecionado
        $schema = new SchemaInspector("mysql:host=$host;dbname=$nome_banco",$usuario_banco,$senha_banco);

        // Insercao do Banco
        $bancoService = new BancoService();
        $bancoService->insert($fk_usuario,$nome_banco,$usuario_banco,$senha_banco,$hostEporta[0],$hostEporta[1]);
        $bancoEspecifico = $bancoService->getBancoEspecifico($nome_banco,$usuario_banco,$fk_usuario);
        // Insercao da Tabela e atributos
        $tabelaService = new TabelaService();
        $tabelas = $schema->getTabelas();
        
        
        $atributoService = new AtributoService();
        foreach($tabelas as $key => $value){
            $tabelaService->insert($value[0],$bancoEspecifico['id_banco']);
            $tabelaEspecifica = $tabelaService->getTabelaEspecifica($value[0],$bancoEspecifico['id_banco']);
            // print_r($schema->getAtributos($value[0]));
            foreach($schema->getAtributos($value[0]) as $att){
                $pk = $att['Key']=="PRI" ? 1 : 0;
                $nn = $att['Null']=="NO" ? 1 : 0;
                $atributoService->insert($tabelaEspecifica['id_tabela'],null,$att['Field'],$att['Type'],$pk,$nn,0,0);
            }
        }
        // print_r($tabelaService->getAllTabelas());
        // print_r($tabelaService->getTabelasByFk_banco(3));
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