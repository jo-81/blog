<?php

namespace Blog\Core\Abstract;

use Blog\Core\Form\FormInterface;
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

    public function createForm(string $formTypeName): FormInterface
    {
        /** @var FormInterface */
        $formType = new $formTypeName();
        foreach ($formType->getFields() as $name => $datas) {
            /** @phpstan-ignore-next-line */
            $formType->addField($name, $datas['type'], $datas['label'] ?? $name, $datas['options'] ?? []);
        }

        return $formType;
    }
}
