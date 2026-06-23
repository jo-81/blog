<?php

use Framework\Http\Router\Route;
use App\Controller\TestController;

return [
    'app.routes' => [
        new Route(['GET'], "/", [TestController::class, 'index'], 'homepage'),
        new Route(['GET'], "/posts", [TestController::class, 'postsList'], 'post.index'),
    ],
];