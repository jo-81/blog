<?php

namespace Framework\Router;

class Route
{
    /**
     * __construct.
     *
     * @param array<string, mixed> $parameters
     *
     * @return void
     */
    public function __construct(
        private string $name,
        private string $path,
        private string $callable,
        private string $method = 'GET',
        private array $parameters = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getCallable(): string
    {
        return $this->callable;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * getParameters.
     *
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
