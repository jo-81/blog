<?php

use DI\ContainerBuilder;
use GuzzleHttp\Psr7\ServerRequest;

$autoload = dirname(__DIR__) . "/vendor/autoload.php";
if (! file_exists($autoload)) {
    throw new RunTimeException("Le fichier d'autoload n'existe pas");
}

include $autoload;

// Ajout des variables d'environnements
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Ajoute des fichiers de configuration dans le container
$configurationFolder = dirname(__DIR__) . "/config";
$fileConfigurations = array_diff(scandir($configurationFolder), ['..', '.']);
$builder = new ContainerBuilder();

foreach ($fileConfigurations as $file) {
    $builder->addDefinitions($configurationFolder . "/$file");
}

$container = $builder->build();

$app = $container->get('app.front_controller');
$response = $app->start(ServerRequest::fromGlobals());

\Http\Response\send($response);
