<?php

declare(strict_types=1);

namespace Framework\Renderer\Twig;

use Twig\Environment;
use Framework\Renderer\Exception\RendererException;
use Framework\Renderer\Interface\RendererInterface;

final class TwigRenderer implements RendererInterface
{
    public function __construct(private Environment $twig)
    {
    }

    public function render(string $template, array $params = []): string
    {
        try {
            return $this->twig->render($template, $params);
        } catch (\Throwable $th) {
            throw new RendererException($th->getMessage());
        }
    }

    public function isTemplateExists(string $template): bool
    {
        return $this->twig->getLoader()->exists($template);
    }

    /**
     * loadExtensions
     *
     * @param  mixed[] $extensions
     * @return static
     */
    public function loadExtensions(array $extensions): static
    {
        foreach ($extensions as $extension) {
            $this->twig->addExtension($extension);
        }

        return $this;
    }

    public function addGlobal(string $key, mixed $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}
