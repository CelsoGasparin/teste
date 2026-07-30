<?php
namespace app\model;

use app\repositories\ProjetoRepository;
use Throwable;

class Tabela{
    public readonly string $nomeTabela;
    public readonly string $nomeTabelaUC;
    public readonly ?array $attributes;
    private ?array $attInputs;



    public function __construct($nomeTabela){
        try{
            $projetoRepository = new ProjetoRepository();
            $this->nomeTabela = $nomeTabela;
            $this->nomeTabelaUC = ucfirst($this->nomeTabela);
            $this->attributes = $projetoRepository->getAtributos($this->nomeTabela);
            $this->attInputs = [];
           
            foreach($this->attributes as $att){
                if($att->Key!='PRI')$this->attInputs[] = Tabela::inputs($att->Type,$att->Field);
            }
        }catch(Throwable $th){
            throw $th;
        }
    }

    public function getAttInputs(){
        return $this->attInputs;
    }

    private static function inputs($type,$name){
        switch(true){
            case $type=='int':
                return "<input type='number' name='$name' value='<?= \$obj?\$obj['{$name}']:''?>'><br>\n";
            break;
            case substr($type,0,7)=='decimal':
                return "<input type='number' step='0.01' name='$name'value='<?= \$obj?\$obj['{$name}']:''?>'><br>\n";
            break;
            
            case $type =='text':
            case substr($type,0,4) =='char':
            case substr($type,0,7) == 'varchar':
                return "<input type='text' name='$name' value='<?= \$obj?\$obj['{$name}']:''?>'><br>\n";
            break;
            
            case $type=='date':
                return "<input type='date' name='$name' value='<?= \$obj?\$obj['{$name}']:''?>'><br>\n";
            break;

            case $type=='year':
                return "<input type=\"number\" min=\"0\" max=\"2077\" step=\"1\" value=\"2000\" name='$name' value='<?= \$obj?\$obj['{$name}']:''?>'>";
            break;

            case substr($type,0,4)=='enum':
                $tipoString = str_ireplace(['enum(',')',"'",'"'],'',$type);
                $result = "<select name=\"$name\" id=\"$name\">\n";
                $result .= "<option value=\"\"><b>--</b></option>\n";
                foreach(explode(',',$tipoString) as $tipo){
                    $result .= "<option value=\"$tipo\" <?= \$obj? (\$obj['{$name}']=='{$tipo}' ? 'selected' : null) : null ?> ><b>$tipo</b></option>\n";
                }
                $result .= "</select>\n";

                return $result;
            break;
            
            default:
                return"<p><b>Erro:o tipo de atributo $type não é suportado por essa joça(jossa é com ç ou com ss?)</b></p>";
            break;
        }
    }   
}
