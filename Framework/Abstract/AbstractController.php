<?php

declare(strict_types=1);

namespace Framework\Abstract;

use DI\NotFoundException;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Renderer\Interface\RendererInterface;
use Framework\Http\Interface\ResponseFactoryInterface;

abstract class AbstractController
{
    /** @var mixed[] */
    protected array $containers;

    protected function getContainer(string $key): mixed
    {
        if (! isset($this->containers[$key])) {
            throw new NotFoundException(sprintf("La clé %s n'existe pas dans le container", $key));
        }

        return $this->containers[$key];
    }

    /**
     * @param  mixed[] $params
     */
    protected function render(string $template, array $params = [], int $statusCode = 200): AppResponseInterface
    {
        /** @var ResponseFactoryInterface */
        $response = $this->getContainer('response');
        /** @var RendererInterface */
        $renderer = $this->getContainer('renderer');

        return $response->createHtmlResponse($renderer->render($template, $params), $statusCode);
    }
}
