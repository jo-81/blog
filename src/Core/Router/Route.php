<?php

namespace Blog\Core\Router;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD)]
final class Route
{
    /** @var array<mixed> */
    private array $parameters = [];

    private string $action;

    public function __construct(private string $path, private string $name, private string $method = "GET")
    {
        $this->path = trim($path, '/');
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function match(string $url): ?self
    {
        $regex = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);

        if (preg_match("#^$regex$#", trim($url, '/'), $matches)) {
            $this->parameters = array_slice($matches, 1);

            return $this;
        }

        return null;
    }

    public function getAction(?int $key = null): string
    {
        if (is_null($key)) {
            return $this->action;
        }

        $actions = explode('#', $this->action);
        if (! array_key_exists($key, $actions)) {
            return $this->action;
        }

        return $actions[$key];
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * getParameters
     *
     * @return array<mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
