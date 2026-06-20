<?php

return [
    'settings.debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'settings.env'   => $_ENV['APP_ENV'] ?? 'production',
];