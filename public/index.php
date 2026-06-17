<?php

use Framework\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use App\Middlewares\MessageTestMiddleware;
use Nyholm\Psr7Server\ServerRequestCreator;

require_once dirname(__DIR__) . "/vendor/autoload.php";

// 1. On fabrique la requête PSR-7
$psr17Factory = new Psr17Factory();
$request = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

// 2. On prépare la file d'attente (la queue) avec notre middleware fait maison
$queue = [
    new MessageTestMiddleware()
];

// 3. On initialise Relay avec cette liste
$relay = new \Relay\Relay($queue);

// 4. On passe Relay à notre Kernel
$kernel = new Kernel($relay);

// 5. On récupère la réponse du Kernel
$response = $kernel->handle($request->fromGlobals());

// 6. On envoie les en-têtes HTTP au navigateur
http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}

// 7. On affiche le corps de la réponse
echo $response->getBody();
