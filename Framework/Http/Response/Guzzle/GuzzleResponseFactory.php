<?php

declare(strict_types=1);

namespace Framework\Http\Response\Guzzle;

use Framework\Http\Interface\AppResponseInterface;
use Framework\Http\Interface\ResponseFactoryInterface;

class GuzzleResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200): AppResponseInterface
    {
        return new GuzzleResponse($code);
    }

    public function createHtmlResponse(string $html, int $code = 200): AppResponseInterface
    {
        return new GuzzleResponse(
            $code,
            ['Content-Type' => 'text/html; charset=utf-8'],
            $html
        );
    }

    public function createRedirectResponse(string $uri, int $code = 302): AppResponseInterface
    {
        return new GuzzleResponse(
            $code,
            ['Location' => $uri]
        );
    }
}
