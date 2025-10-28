<?php

declare(strict_types=1);

namespace Framework\Http\Interface;

interface ResponseFactoryInterface
{
    public function createResponse(int $code = 200): AppResponseInterface;

    public function createHtmlResponse(string $html, int $code = 200): AppResponseInterface;

    public function createRedirectResponse(string $uri, int $code = 302): AppResponseInterface;
}
