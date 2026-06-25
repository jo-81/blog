<?php

declare(strict_types=1);

namespace Framework\Renderer;

use Psr\Http\Message\ResponseInterface;

/**
 * Définit le contrat pour le moteur de rendu de templates du framework.
 */
interface RendererInterface
{
    /**
     * Génère le rendu d'un template et retourne le HTML brut.
     *
     * @param string $template Le chemin ou le nom du template (ex: 'home.twig').
     * @param array<string, mixed> $data Les variables à passer au template.
     * @return string Le code HTML généré.
     */
    public function render(string $template, array $data = []): string;

    /**
     * Génère le rendu d'un template et l'injecte dans une réponse HTTP PSR-7.
     *
     * @param ResponseInterface $response La réponse HTTP de base à modifier.
     * @param string $template Le chemin ou le nom du template.
     * @param array<string, mixed> $data Les variables à passer au template.
     * @return ResponseInterface La réponse HTTP complétée avec le HTML et le Content-Type.
     */
    public function renderResponse(ResponseInterface $response, string $template, array $data = []): ResponseInterface;

    /**
     * Ajoute une variable globale accessible dans tous les templates.
     *
     * @param string $name Le nom de la variable dans le template.
     * @param mixed $value La valeur de la variable.
     */
    public function addGlobal(string $name, mixed $value): void;

    /**
     * Vérifie si un template existe.
     *
     * @param string $template Le nom du template à vérifier.
     */
    public function exists(string $template): bool;
}
