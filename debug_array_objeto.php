<?php
function arrayObjDebug($variable, $html = false, $maxDepth = 100, $maxWidth = 25, $depth = 0, &$objects = array())
{
    $type = gettype($variable);
    
    $output = '';
    if ($type == 'boolean'):
        $output .= $variable ? 'true' : 'false';
    elseif ($type == 'integer' || $type == 'double'):
        $output .= $variable;
    elseif ($type == 'NULL'):
        $output .= 'null';
    elseif ($type == 'string'):
        $output .= formatString($variable, $maxDepth);
    elseif ($type == 'array'):
        $output .= formatArray($variable, $html, $maxDepth, $maxWidth, $depth, $objects);
    elseif ($type == 'object'):
        $output .= formatObject($variable, $html, $maxDepth, $maxWidth, $depth, $objects);
    else:
        $output .= 'undefined';
    endif;
    
    if ($depth == 0) {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        do {
            $call = array_shift($backtrace);
        } while ($call && !isset($call['file']));

        if ($call) {
            $output = "Arquivo que chamou: " . $call['file'] . "\n" . "Linha que chamou a Function : " . $call['line'] . "\n" . $output . ';';
            if ($html) {
                echo nl2br(str_replace(' ', '&nbsp;', htmlentities($output)));
            } else {
                echo $output;
            }
        }
    }
    
    return $output;
}

function formatString($string, $maxLength)
{
    $string = str_replace(
        array("\0", "\a", "\b", "\f", "\n", "\r", "\t", "\v"),
        array('\0', '\a', '\b', '\f', '\n', '\r', '\t', '\v'),
        substr($string, 0, $maxLength),
        $count
    );
    $string = substr($string, 0, $maxLength);
    $length = strlen($string);
    
    if ($length < $maxLength) {
        return '"' . $string . '"';
    } else {
        return 'Tamanho String(' . $length . '): "' . $string . '"';
    }
}

function formatArray($array, $html, $maxDepth, $maxWidth, $depth, &$objects)
{
    $length = count($array);
   
    if (!$length) {
        return 'array(0) {}';
    } else {
        $keys = array_keys($array);
        $indentation = str_repeat(' ', $depth * 2);
        $output = $indentation . '[';
        $count = 0;
        foreach ($keys as $key) {
            if ($count == $maxWidth) {
                $output .= "\n" . $indentation;
                break;
            }
            $output .= "\n" . $indentation . "  $key => " . arrayObjDebug($array[$key], $html, $maxDepth, $maxWidth, $depth + 1, $objects) . ',';
            $count++;
        }
        $output .= "\n" . $indentation . ']';
        return $output;
    }
}

function formatObject($object, $html, $maxDepth, $maxWidth, $depth, &$objects)
{
    $id = array_search($object, $objects, true);
    
    if ($id !== false) {
        return get_class($object) . '#' . ($id + 1) . ' {...}';
    } else {
        $array = (array)$object;
        $indentation = str_repeat(' ', $depth * 2);
        $output = $indentation . '[';
        $properties = array_keys($array);
        foreach ($properties as $property) {
            $name = str_replace("\0", ':', trim($property));
            $output .= "\n" . $indentation . "  '$name' => " . arrayObjDebug($array[$property], $html, $maxDepth, $maxWidth, $depth + 1, $objects) . ',';
        }
        $output .= "\n" . $indentation . ']';
        return $output;
    }
}

$dellRey = [
    0 => ['regra_quebrada' => 'vlAtualBeneficio', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '28'],
    1 => ['regra_quebrada' => 'dtUltimaAtualizacao', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '28'],
    2 => ['regra_quebrada' => 'vlAtualBeneficio', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '96'],
    3 => ['regra_quebrada' => 'dtUltimaAtualizacao', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '96'],
    4 => ['regra_quebrada' => 'vlAtualBeneficio', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '96']
];

echo arrayObjDebug($dellRey, true);

