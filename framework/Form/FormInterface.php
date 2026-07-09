<?php

declare(strict_types=1);

namespace Framework\Form;

use Psr\Http\Message\ServerRequestInterface;

interface FormInterface
{
    public function handleRequest(ServerRequestInterface $request): self;

    public function isSubmitted(): bool;

    public function isValid(): bool;

    public function getData(): mixed;

    public function createView(): array;
}
