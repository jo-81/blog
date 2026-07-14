<?php

return [
    'access_control' => [
        '/dashboard' => ['admin'],
        '^/admin' => ['admin'],
    ]
];