<?php

use DI\ContainerBuilder;
use GuzzleHttp\Psr7\ServerRequest;

require dirname(__DIR__) . "/vendor/autoload.php";

$configurationFolder = dirname(__DIR__) . "/config";
if (! file_exists($configurationFolder)) {
    throw new Exception("Le dossier de configuration n'existe pas");
}

$configurationFiles = array_diff(scandir($configurationFolder), ['..', '.']);
$containerBuilder = new ContainerBuilder();
foreach($configurationFiles as $file) {
    $containerBuilder->addDefinitions($configurationFolder .'/'. $file);
}

$container = $containerBuilder->build();
$app = $container->get('app');

$response = $app->run(ServerRequest::fromGlobals());