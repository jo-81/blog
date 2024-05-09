<?php

use Framework\Application;

return [
    "app" => DI\create(Application::class)->method("setMiddlewareHandler", DI\get("app.middleware_handle"))
];