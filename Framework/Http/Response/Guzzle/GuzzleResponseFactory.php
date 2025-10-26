<?php

declare(strict_types=1);

namespace Framework\Http\Response\Guzzle;

use Framework\Http\Interface\ResponseInterface;
use Framework\Http\Interface\ResponseFactoryInterface;

class GuzzleResponseFactory implements ResponseFactoryInterface
{
    public function factory(): ResponseInterface
    {
        return new GuzzleResponse();
    }
}
