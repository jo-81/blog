<?php

namespace Blog\Core\Abstract;

use Blog\Core\Renderer\RendererInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractController
{
    protected RendererInterface $renderer;

    /**
     * render
     *
     * @param  string $template
     * @param  array<mixed> $parameters
     * @return ResponseInterface
     */
    public function render(string $template, array $parameters = []): ResponseInterface
    {
        return new Response(200, [], $this->renderer->render($template, $parameters));
    }
}
