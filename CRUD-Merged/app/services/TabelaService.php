<?php
namespace app\services;

use app\repositories\TabelaRepository;

class TabelaService{
    private TabelaRepository $tabela_repository;

    public function __construct(){
        $this->tabela_repository = new TabelaRepository;
    }



    public function getTabela($value,$param = "tabela.id_tabela"){
        $result = $this->tabela_repository->getTabela($value,$param);
        return $result;
    }


}