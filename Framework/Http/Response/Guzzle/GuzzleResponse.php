<?php

declare(strict_types=1);

namespace Framework\Http\Response\Guzzle;

use GuzzleHttp\Psr7\Response;
use Framework\Http\Interface\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class GuzzleResponse implements ResponseInterface
{
    public function createResponse(int $code = 200): Psr7ResponseInterface
    {
        return new Response($code);
    }

    public function createHtmlResponse(string $html, int $code = 200): Psr7ResponseInterface
    {
        return new Response($code, ['Content-Type' => 'text/html; charset=utf-8'], $html);
    }
}
