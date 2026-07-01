<?php

declare(strict_types=1);

namespace Framework\Renderer\TwigExtensions;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Framework\Session\SessionInterface;
use Framework\Security\CsrfTokenManagerInterface;

class CsrfExtension extends AbstractExtension
{
    public function __construct(
        private CsrfTokenManagerInterface $tokenManager,
        private SessionInterface $session,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('csrf_token', [$this, 'renderCsrfToken'], ['is_safe' => ['html']]),
        ];
    }

    public function renderCsrfToken(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . $this->tokenManager->getToken($this->session) . '">';
    }
}
