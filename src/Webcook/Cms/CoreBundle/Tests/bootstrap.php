<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

if (!is_file($autoloadFile = __DIR__.'/../../../../../../../autoload.php')) {
    if (!is_file($autoloadFile = __DIR__.'/../../../../../vendor/autoload.php')) {
        throw new \LogicException('Run "composer install --dev" to create autoloader.');
    }
}

$loader = require $autoloadFile;

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
