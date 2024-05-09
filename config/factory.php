<?php

use Framework\Factory\RouterFactory;
use Framework\Router\Adapter\AltoRouterAdapter;

return [
    "app.router_interface" => DI\factory(RouterFactory::class),
    "app.router_adapter" => DI\autowire(AltoRouterAdapter::class),
];