<?php

$autoloadFile = __DIR__.'/../vendor/autoload.php';

if (!is_file($autoloadFile)) {
    throw new RuntimeException('Could not find autoloader. Did you run "composer.phar install --dev"?');
}

$loader = require $autoloadFile;

$loader->add('JMS\Tests', __DIR__);
