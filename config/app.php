<?php

use Twig\Extension\DebugExtension;

use function DI\autowire;

return [
    'app.twig_extensions' => [
        DebugExtension::class => autowire(DebugExtension::class),
    ]
];