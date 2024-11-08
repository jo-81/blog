<?php

use Blog\Controller\Front\HomeController;

use function DI\create;
use function DI\get;

return [
    HomeController::class => create(HomeController::class)
        ->property('renderer', get("app.renderer_interface")),
];