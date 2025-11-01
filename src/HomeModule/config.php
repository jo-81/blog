<?php

declare(strict_types=1);

use Framework\Router\Route;
use Blog\HomeModule\Controller\HomeController;

return [
    'app.routes' => DI\add([
        new Route('homepage', '/', HomeController::class."@index")
    ]),
    'app.renderer_paths' => DI\add([__DIR__ . "/templates"])
];
