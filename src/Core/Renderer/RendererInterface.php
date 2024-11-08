<?php

namespace Blog\Core\Renderer;

interface RendererInterface
{
    public function addPath(string $namespace, string $path): void;

    /**
     * render
     *
     * @param  string $view
     * @param  array<mixed> $params
     * @return string
     */
    public function render(string $view, array $params = []): string;

    public function addGlobal(string $key, mixed $value): void;
}
