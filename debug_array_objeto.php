<?php
function array_obj_debug($variavel,$html=false,$maximo=100,$largura=25,$elemento=0,&$objetos = array())
{
    $busca        = array("\0", "\a", "\b", "\f", "\n", "\r", "\t", "\v");
    $substituir   = array('\0', '\a', '\b', '\f', '\n', '\r', '\t', '\v');
    $tipoEntrada  = gettype($variavel);
    
    $string = '';
    if ($tipoEntrada == 'boolean'):
    $string.= $variavel?'true':'false';
    elseif ($tipoEntrada == 'integer'):
    $string.= $variavel;
    elseif ($tipoEntrada == 'double'):
    $string.= $variavel; 
    elseif ($tipoEntrada == 'double'):
    $string.= $variavel;
    elseif ($tipoEntrada == 'resource'):
    $string.= '[resource]';
    elseif ($tipoEntrada == 'NULL'):
    $string.= "null";
    elseif ($tipoEntrada == 'unknown type'):
    $string.= 'undefined';
    
    elseif ($tipoEntrada == 'string'):
    $tamanho  = strlen($variavel);
    $variavel = str_replace($busca,$substituir,substr($variavel,0,$maximo),$count);
    $variavel = substr($variavel,0,$maximo);
    if ($tamanho<$maximo){
        $string.= '"'.$variavel.'"';
    } else {
        $string.= 'Tamanho String('.$tamanho.'): "'.$variavel.'"';
    }
    
    elseif ($tipoEntrada == 'array'):            
    $tamanho = count($variavel);
   
    if(!$tamanho):
        $string.= 'array(0) {}';
    
    else:
        $keys        = array_keys($variavel);
        $espacamento = str_repeat(' ',$elemento*2);
        $string.= $espacamento.'[';
        $count  = 0;
        foreach($keys as $key):
            if ($count == $largura):
                $string.= "\n".$espacamento;
                break;
            endif;
            $string.= "\n".$espacamento."  $key => ";
            $string.= array_obj_debug($variavel[$key],$html,$maximo,$largura,$elemento+1,$objetos).',';
            $count++;
        endforeach;
        $string.="\n".$espacamento.']';
    endif;
    
    elseif ($tipoEntrada == 'object'):         
    $id = array_search($variavel,$objetos,true);
    if ($id !== false):
        $string.= get_class($variavel).'#'.($id+1).' {...}';
    
    else:
        $array       = (array)$variavel;
        $espacamento = str_repeat(' ',$elemento*2);
        $string.= '[';
        $properties = array_keys($array);
        foreach($properties as $property) {
            $name = str_replace("\0",':',trim($property));
            $string.= "\n".$espacamento."  '$name' => ";
            $string.= array_obj_debug($array[$property],$html,$maximo,$largura,$elemento+1,$objetos).',';
        }
        $string.= "\n".$espacamento.']';
    endif;
    
    endif;
    
    if ($elemento>0):
    return $string;
    endif;
    
    $backtrace  = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    do $chamada = array_shift($backtrace);
    while ($chamada && !isset($chamada['file']));
    
    if ($chamada):
        $string = "Arquivo que chamou: ".$chamada['file']."\n"."Linha que chamou a Function : ".$chamada['line']."\n".$string.';';
        if($html === true):
            echo nl2br(str_replace(' ','&nbsp;',htmlentities($string)));
        else:
            echo $string;
        endif;
    endif;
}

$dellRey = [
  0 => 
    [
      'regra_quebrada' =>  'vlAtualBeneficio',
      'nome_tag' =>  'beneficioServidor' ,
      'id_vinculado' =>  '28'],
  1 => 
    [
      'regra_quebrada' =>  'dtUltimaAtualizacao',
      'nome_tag' =>  'beneficioServidor' ,
      'id_vinculado' =>  '28'] ,
  2 => 
    [
      'regra_quebrada' =>  'vlAtualBeneficio' ,
      'nome_tag' =>  'beneficioServidor',
      'id_vinculado' =>  '96'],
  3 => 
    [
      'regra_quebrada' =>  'dtUltimaAtualizacao',
      'nome_tag' =>  'beneficioServidor',
      'id_vinculado' =>  '96' ],
  4 => 
    [
      'regra_quebrada' =>  'vlAtualBeneficio' ,
      'nome_tag' =>  'beneficioServidor',
      'id_vinculado' =>  '96']
];

echo array_obj_debug($dellRey, true);


?>
