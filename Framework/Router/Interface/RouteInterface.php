<?php

declare(strict_types=1);

namespace Framework\Router\Interface;

interface RouteInterface
{
    public function getMethod(): string;

    public function getName(): string;

    public function getTarget(): string;

    public function getPath(): string;

    /**
     * getParameters
     *
     * @return mixed[]
     */
    public function getParameters(): array;

    /**
     * @param  mixed[] $parameters
     */
    public function setParameters(array $parameters): static;
}
