<?php

include(__DIR__ . '/../vendor/autoload.php');

$configPath = __DIR__ . '/../app/config/settings.php';
$localConfigPath = __DIR__ . '/../app/config/local_settings.php';

$debug = false;
if (is_file($localConfigPath)) {
    $configPath = $localConfigPath;
    $debug = true;
}

define("PHACT_DEBUG", $debug);

$config = include $configPath;
\Phact\Main\Phact::init($config);
\Phact\Main\Phact::app()->run();