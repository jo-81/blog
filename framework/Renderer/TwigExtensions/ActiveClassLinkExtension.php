<?php

namespace Framework\Renderer\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ActiveClassLinkExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('active_class', [$this, 'renderActiveClassLink'], ['is_safe' => ['html']]),
        ];
    }

    public function renderActiveClassLink(string $label, ?string $activeLink): string
    {
        if (is_null($activeLink)) {
            return '';
        }

        return $label == $activeLink ? 'active': '';
    }
}