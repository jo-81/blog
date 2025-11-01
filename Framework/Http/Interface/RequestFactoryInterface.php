<?php

declare(strict_types=1);

namespace Framework\Http\Interface;

interface RequestFactoryInterface
{
    public function createFromGlobals(): AppRequestInterface;
}
