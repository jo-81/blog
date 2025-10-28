<?php

declare(strict_types=1);

namespace Framework\Http\Request\Guzzle;

use Framework\Http\Interface\AppRequestInterface;
use Framework\Http\Interface\RequestFactoryInterface;

class GuzzleRequestFactory implements RequestFactoryInterface
{
    public function factory(): AppRequestInterface
    {
        return GuzzleRequest::createFromGlobals();
    }
}
