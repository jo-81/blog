<?php

use DI\ContainerBuilder;

require dirname(__DIR__) . "/vendor/autoload.php";

$configuration = dirname(__DIR__) . "/config";
if (! file_exists($configuration)) {
    throw new Exception("Le dossier de configuration n'existe pas");
}

$configurationFiles = array_diff(scandir($configuration), ['..', '.']);

$containerBuilder = new ContainerBuilder();
foreach($configurationFiles as $file) {
    $containerBuilder->addDefinitions($file);
}

$container = $containerBuilder->build();