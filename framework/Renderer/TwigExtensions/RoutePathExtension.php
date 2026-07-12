<?php

namespace Framework\Renderer\TwigExtensions;

use Framework\Http\Router\UrlGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RoutePathExtension extends AbstractExtension
{
    public function __construct(private UrlGenerator $urlGenerator)
    {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this, 'renderPath'], ['is_safe' => ['html']]),
        ];
    }

    public function renderPath(string $routeName, array $params = [])
    {
        return $this->urlGenerator->generate($routeName, $params);
    }
}