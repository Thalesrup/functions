<?php

     function validar_regra($string)
    {
        $tabelaRegimePrevidenciario = array(
            1 => array('REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL', 'RPPS'),
            2 => 'REGIME PRÓPRIO MILITAR',
            3 => array('REGIME PRÓPRIO EM EXTINÇÃO', 'RPPS Em Extinção'),
            4 => 'REGIME PRÓPRIO MILITAR EM EXTINÇÃO',
            5 => array('REGIME GERAL DE PREVIDÊNCIA SOCIAL (INSS)','RGPS')
        );

        $codigoRegimePrevidenciario = array_search($string, $tabelaRegimePrevidenciario);
         
         // Se for encontrada uma chave referente a sring informada ele ira 
         // retornar um int se não encontrar retorna false e cai na condição abaixo
         
         if(is_bool($codigoRegimePrevidenciario)){
            return valida_array_multidimenssional($string, $tabelaRegimePrevidenciario);
        }
        return $codigoRegimePrevidenciario;
    }

    
    // Function Retorna o Indice($codigo) correspondente ao dado que esta sendo buscado dentro da Array-Multidimenssional
    function valida_array_multidimenssional($codigoParametro,$arrayTabela)
    {
        foreach($arrayTabela as $codigo => $valor){
            if(is_array($valor)){
                for($i = 0;$i < count($valor);$i++){
                    $validaArray = array_search($codigoParametro, $valor);
                    if(is_int($validaArray)){
                        return $codigo;
                        break;
                    }
                }
            }
        }
    }

echo validar_regra('RGPS');
