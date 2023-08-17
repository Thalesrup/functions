<?php

function validarRegra($string)
{
    $tabelaRegimePrevidenciario = [
        1 => ['REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL', 'RPPS'],
        2 => 'REGIME PRÓPRIO MILITAR',
        3 => ['REGIME PRÓPRIO EM EXTINÇÃO', 'RPPS Em Extinção'],
        4 => 'REGIME PRÓPRIO MILITAR EM EXTINÇÃO',
        5 => ['REGIME GERAL DE PREVIDÊNCIA SOCIAL (INSS)', 'RGPS']
    ];

    $codigoRegimePrevidenciario = array_search($string, array_column($tabelaRegimePrevidenciario, 0), true);

    if ($codigoRegimePrevidenciario === false) {
        return validaArrayMultidimensional($string, $tabelaRegimePrevidenciario);
    }
    
    return $codigoRegimePrevidenciario;
}

function validaArrayMultidimensional($codigoParametro, $arrayTabela)
{
    foreach ($arrayTabela as $codigo => $valor) {
        if (is_array($valor)) {
            if (in_array($codigoParametro, $valor)) {
                return $codigo;
            }
        }
    }
}

echo validarRegra('RGPS');
