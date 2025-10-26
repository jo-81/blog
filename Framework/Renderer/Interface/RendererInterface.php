<?php

declare(strict_types=1);

namespace Framework\Renderer\Interface;

interface RendererInterface
{
    /**
     * @param  mixed[] $params
     */
    public function render(string $template, array $params = []): string;

    public function isTemplateExists(string $template): bool;

    /**
     * loadExtensions
     *
     * @param  mixed[] $extensions
     */
    public function loadExtensions(array $extensions): static;

    public function addGlobal(string $key, mixed $value): void;
}
