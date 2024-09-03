<?php

$stream = new StreamHandler();

$arrayData = [
    "Linha 1\n",
    "Linha 2\n",
    "Linha 3\n"
];

$stream->write(implode('', $arrayData));

foreach ($stream->getIterator() as $line) {
    echo $line;
}

$stream->close();
