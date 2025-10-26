<?php

declare(strict_types=1);

namespace Framework\Http\Request\Guzzle;

use Framework\Http\Interface\RequestInterface;
use Framework\Http\Interface\RequestFactoryInterface;

class GuzzleRequestFactory implements RequestFactoryInterface
{
    public function factory(): RequestInterface
    {
        return new GuzzleRequest();
    }
}
