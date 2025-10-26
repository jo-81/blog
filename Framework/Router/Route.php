<?php

declare(strict_types=1);

namespace Framework\Router;

use Framework\Router\Interface\RouteInterface;

class Route implements RouteInterface
{
    /** @var mixed[] */
    private array $parameters = [];

    public function __construct(
        private string $name,
        private string $path = '',
        private string $target = '',
        private string $method = 'GET'
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * getParameters
     *
     * @return mixed[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param  mixed[] $parameters
     */
    public function setParameters(array $parameters): static
    {
        $this->parameters = $parameters;

        return $this;
    }
}
