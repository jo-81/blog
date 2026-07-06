<?php

declare(strict_types=1);

namespace Framework\Renderer\TwigExtensions;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Framework\Session\MessageFlashInterface;

class MessageFlashExtension extends AbstractExtension
{
    public function __construct(private MessageFlashInterface $flash) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_flashes', [$this, 'renderFlashes']),
        ];
    }

    public function renderFlashes(?string $type = null): array
    {
        if ($type !== null) {
            return $this->flash->get($type);
        }

        return $this->flash->all();
    }
}
