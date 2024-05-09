<?php

use App\Tests\Fixtures\Controller\ExampleController;

return [
    "app.routes" => [
        ['name' => 'homepage', 'path' => '/', 'callable' => ExampleController::class.'#index'],
        ['name' => 'post.show', 'path' => '/posts/[i:id]', 'callable' => ExampleController::class.'#postShow'],
        ['name' => 'post.list', 'path' => '/posts', 'callable' => ExampleController::class.'#postList'],
    ]
];
