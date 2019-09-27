
    PHP
    
    // Function Retorna o Indice($codigo) correspondente ao dado que esta sendo buscado dentro da Array-Multidimenssional
    public function valida_array_multidimenssional($codigoParametro,$arrayTabela)
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
