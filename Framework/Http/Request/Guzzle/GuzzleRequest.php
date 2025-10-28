<?php

declare(strict_types=1);

namespace Framework\Http\Request\Guzzle;

use GuzzleHttp\Psr7\ServerRequest;
use Framework\Http\Interface\AppRequestInterface;

class GuzzleRequest extends ServerRequest implements AppRequestInterface
{
    public static function createFromGlobals(): self
    {
        $serverRequest = ServerRequest::fromGlobals();

        return new self(
            $serverRequest->getMethod(),
            $serverRequest->getUri(),
            $serverRequest->getHeaders(),
            $serverRequest->getBody(),
            $serverRequest->getProtocolVersion(),
            $serverRequest->getServerParams()
        );
    }
}
