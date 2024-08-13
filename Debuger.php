
<?php

class Debugger
{
    private $html;
    private $maxDepth;
    private $maxWidth;
    private $objects = [];

    public function __construct($html = false, $maxDepth = 100, $maxWidth = 25)
    {
        $this->html = $html;
        $this->maxDepth = $maxDepth;
        $this->maxWidth = $maxWidth;
    }

    public function debug($variable)
    {
        $output = $this->handleVariable($variable);

        if ($this->isRootCall()) {
            $this->outputDebugInfo($output);
        }

        return $output;
    }

    private function handleVariable($variable, $depth = 0)
    {
        $type = gettype($variable);

        $handler = match ($type) {
            'boolean' => new BooleanHandler(),
            'integer', 'double' => new NumericHandler(),
            'NULL' => new NullHandler(),
            'string' => new StringHandler($this->maxDepth),
            'array' => new ArrayHandler($this->maxWidth, $this->maxDepth),
            'object' => new ObjectHandler($this->objects, $this->maxWidth, $this->maxDepth),
            default => new UndefinedHandler(),
        };

        return $handler->handle($variable, $this, $depth);
    }

    private function isRootCall()
    {
        return count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)) === 1;
    }

    private function outputDebugInfo($output)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $call = array_shift($backtrace);

        $output = "Arquivo que chamou: " . $call['file'] . "\nLinha que chamou a Function: " . $call['line'] . "\n" . $output . ';';

        if ($this->html) {
            echo nl2br(str_replace(' ', '&nbsp;', htmlentities($output)));
        } else {
            echo $output;
        }
    }
}

interface TypeHandler
{
    public function handle($variable, Debugger $debugger, $depth);
}

class BooleanHandler implements TypeHandler
{
    public function handle($variable, Debugger $debugger, $depth)
    {
        return $variable ? 'true' : 'false';
    }
}

class NumericHandler implements TypeHandler
{
    public function handle($variable, Debugger $debugger, $depth)
    {
        return $variable;
    }
}

class NullHandler implements TypeHandler
{
    public function handle($variable, Debugger $debugger, $depth)
    {
        return 'null';
    }
}

class StringHandler implements TypeHandler
{
    private $maxLength;

    public function __construct($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function handle($variable, Debugger $debugger, $depth)
    {
        $formattedString = str_replace(
            ["\0", "\a", "\b", "\f", "\n", "\r", "\t", "\v"],
            ['\0', '\a', '\b', '\f', '\n', '\r', '\t', '\v'],
            substr($variable, 0, $this->maxLength)
        );

        return (strlen($variable) < $this->maxLength) 
            ? "\"$formattedString\"" 
            : "Tamanho String(" . strlen($variable) . "): \"$formattedString\"";
    }
}

class ArrayHandler implements TypeHandler
{
    private $maxWidth;
    private $maxDepth;

    public function __construct($maxWidth, $maxDepth)
    {
        $this->maxWidth = $maxWidth;
        $this->maxDepth = $maxDepth;
    }

    public function handle($variable, Debugger $debugger, $depth)
    {
        if (empty($variable)) {
            return 'array(0) {}';
        }

        return $this->formatIterable($variable, $debugger, $depth);
    }

    private function formatIterable($iterable, Debugger $debugger, $depth)
    {
        $output = '';
        $indentation = str_repeat(' ', $depth * 2);
        $count = 0;

        foreach ($iterable as $key => $value) {
            if ($count === $this->maxWidth) {
                $output .= "\n" . $indentation;
                break;
            }
            $output .= "\n$indentation  $key => " . $debugger->handleVariable($value, $depth + 1) . ',';
            $count++;
        }

        return "[\n$output\n" . str_repeat(' ', $depth * 2) . "]";
    }
}

class ObjectHandler implements TypeHandler
{
    private $objects;
    private $maxWidth;
    private $maxDepth;

    public function __construct(&$objects, $maxWidth, $maxDepth)
    {
        $this->objects = &$objects;
        $this->maxWidth = $maxWidth;
        $this->maxDepth = $maxDepth;
    }

    public function handle($variable, Debugger $debugger, $depth)
    {
        $id = array_search($variable, $this->objects, true);

        if ($id !== false) {
            return get_class($variable) . '#' . ($id + 1) . ' {...}';
        }

        $this->objects[] = $variable;
        return $this->formatIterable((array)$variable, $debugger, $depth);
    }

    private function formatIterable($iterable, Debugger $debugger, $depth)
    {
        $output = '';
        $indentation = str_repeat(' ', $depth * 2);
        $count = 0;

        foreach ($iterable as $key => $value) {
            if ($count === $this->maxWidth) {
                $output .= "\n" . $indentation;
                break;
            }
            $output .= "\n$indentation  $key => " . $debugger->handleVariable($value, $depth + 1) . ',';
            $count++;
        }

        return "[\n$output\n" . str_repeat(' ', $depth * 2) . "]";
    }
}

class UndefinedHandler implements TypeHandler
{
    public function handle($variable, Debugger $debugger, $depth)
    {
        return 'undefined';
    }
}

// Exemplo de uso
$dellRey = [
    ['regra_quebrada' => 'vlAtualBeneficio', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '28'],
    ['regra_quebrada' => 'dtUltimaAtualizacao', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '28'],
    ['regra_quebrada' => 'vlAtualBeneficio', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '96'],
    ['regra_quebrada' => 'dtUltimaAtualizacao', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '96'],
    ['regra_quebrada' => 'vlAtualBeneficio', 'nome_tag' => 'beneficioServidor', 'id_vinculado' => '96']
];

$debugger = new Debugger(true);
echo $debugger->debug($dellRey);
