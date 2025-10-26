<?php

declare(strict_types=1);

namespace Framework\Http\Interface;

use Psr\Http\Message\ServerRequestInterface;

interface RequestInterface
{
    public function createFromGlobals(): ServerRequestInterface;
}
