<?php

declare(strict_types=1);

namespace Framework\Http\Request\Guzzle;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Interface\RequestInterface;

class GuzzleRequest implements RequestInterface
{
    public function createFromGlobals(): ServerRequestInterface
    {
        return ServerRequest::fromGlobals();
    }

    public function getUri(): string
    {
        return $this->createFromGlobals()->getUri()->getPath();
    }

    public function getMethod(): string
    {
        return $this->createFromGlobals()->getMethod();
    }
}
