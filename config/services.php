<?php

use App\Factories\HttpPipelineFactory;
use Framework\Http\HttpPipelineInterface;

return [
    HttpPipelineInterface::class => DI\factory(HttpPipelineFactory::class),
];