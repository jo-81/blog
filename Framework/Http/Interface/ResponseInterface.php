<?php

declare(strict_types=1);

namespace Framework\Http\Interface;

use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

interface ResponseInterface
{
    public function createResponse(int $code = 200): Psr7ResponseInterface;

    public function createHtmlResponse(string $html, int $code = 200): Psr7ResponseInterface;
}
