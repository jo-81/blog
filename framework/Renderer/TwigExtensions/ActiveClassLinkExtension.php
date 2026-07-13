<?php

declare(strict_types=1);

namespace Framework\Renderer\TwigExtensions;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

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

        return $label == $activeLink ? 'active' : '';
    }
}
