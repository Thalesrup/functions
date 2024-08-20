<?php

$container = new SimpleContainer();

$container->set('logger', new Logger());
$container->set('database', new DatabaseConnection());

try {
    $logger = $container->get('logger');
    $db = $container->get('database');
} catch (NotFoundException $e) {
    echo $e->getMessage();
}

if ($container->has('mailer')) {
    $mailer = $container->get('mailer');
} else {
    echo "Mailer service not found.";
}
