<?php

declare(strict_types=1);

namespace Framework\Http;

use DI\Attribute\Inject;
use Framework\Form\FormInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Form\FormFactoryInterface;
use Framework\Renderer\RendererInterface;
use Framework\Session\MessageFlashInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * Contrôleur de base offrant des fonctionnalités communes à tous les contrôleurs.
 */
abstract class AbstractController
{
    #[Inject]
    protected RendererInterface $renderer;

    #[Inject]
    protected ResponseFactoryInterface $responseFactory;

    #[Inject]
    protected ServerRequestInterface $request;

    #[Inject]
    protected MessageFlashInterface $flash;

    #[Inject]
    protected FormFactoryInterface $formFactory;

    /**
     * Génère une réponse HTTP HTML à partir d'un template.
     *
     * @param string $template Le nom du fichier de template (ex: 'pages/portfolio.twig').
     * @param array<string, mixed> $data Les variables à passer au template.
     * @param int $statusCode Le code de statut HTTP (200 par défaut).
     */
    protected function render(string $template, array $data = [], int $statusCode = 200): ResponseInterface
    {
        $baseResponse = $this->responseFactory->createResponse($statusCode);

        return $this->renderer->renderResponse($baseResponse, $template, $data);
    }

    /**
     * Génère une réponse de redirection HTTP.
     * @param string $url L'URL vers laquelle rediriger
     * @param int $statusCode Le code statut HTTP (302 par défaut)
     */
    protected function redirect(string $url, int $statusCode = 302): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($statusCode);

        return $response->withHeader('Location', $url);
    }

    protected function createForm(string $formType, mixed $data = null, array $options = []): FormInterface
    {
        $form = $this->formFactory->create($formType, $data, $options);

        return $form->handleRequest($this->request);
    }
}
