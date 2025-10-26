<?php

declare(strict_types=1);

namespace Framework\Http\Interface;

interface ResponseFactoryInterface
{
    public function factory(): ResponseInterface;
}
