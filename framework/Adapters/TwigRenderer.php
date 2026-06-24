<?php

declare(strict_types=1);

namespace Framework\Adapters;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;
use Twig\Error\RuntimeError;
use Twig\Extension\ExtensionInterface;
use Psr\Http\Message\ResponseInterface;
use Framework\Renderer\RendererInterface;

/**
 * Adaptateur permettant d'utiliser le moteur de template Twig.
 * Cette classe fait le pont entre l'interface agnostique du Framework
 * et l'environnement natif de Twig.
 */
final class TwigRenderer implements RendererInterface
{
    /**
     * Initialise l'adaptateur avec l'environnement Twig injecté.
     *
     * @param Environment $twig L'instance de configuration de Twig.
     */
    public function __construct(private Environment $twig) {}

    /**
     * Génère le rendu d'une vue et retourne le contenu HTML brut.
     *
     * @param string $view Le nom ou le chemin du fichier de vue (ex: 'pages/home.twig').
     * @param array<string, mixed> $data Les variables à transmettre au template.
     * @return string Le code HTML ou texte généré.
     * @throws LoaderError  Si le fichier de template est introuvable.
     * @throws RuntimeError Si une erreur survient lors de l'exécution ou de la compilation.
     * @throws SyntaxError  Si le template contient une erreur de syntaxe Twig.
     */
    public function render(string $view, array $data = []): string
    {
        return $this->twig->render($view, $data);
    }

    /**
     * Génère le rendu d'une vue et l'injecte directement dans une réponse HTTP PSR-7.
     *
     * @param ResponseInterface $response L'instance de la réponse HTTP à enrichir.
     * @param string $view Le nom ou le chemin du fichier de vue.
     * @param array<string, mixed> $data Les variables à transmettre au template.
     * @return ResponseInterface Une nouvelle instance de la réponse contenant le HTML et le header Content-Type.
     * @throws LoaderError  Si le fichier de template est introuvable.
     * @throws RuntimeError Si une erreur survient lors de l'exécution.
     * @throws SyntaxError  Si le template contient une erreur de syntaxe.
     */
    public function renderResponse(ResponseInterface $response, string $view, array $data = []): ResponseInterface
    {
        $html = $this->render($view, $data);
        $response->getBody()->write($html);

        return $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Ajoute une variable globale accessible dans l'ensemble des templates Twig.
     *
     * @param string $name Le nom de la variable dans les fichiers de vue.
     * @param mixed $value La valeur de la variable (objet, chaîne, tableau, etc.).
     */
    public function addGlobal(string $name, mixed $value): void
    {
        $this->twig->addGlobal($name, $value);
    }

    /**
     * Vérifie si un fichier de vue existe dans les dossiers configurés du Loader.
     *
     * @param string $view Le nom ou le chemin du fichier de vue à tester.
     * @return bool True si le template existe et est accessible, sinon false.
     */
    public function exists(string $view): bool
    {
        try {
            $this->twig->getLoader()->getSourceContext($view);
            return true;
        } catch (LoaderError) {
            return false;
        }
    }

    /**
     * Enregistre une extension personnalisée pour enrichir les fonctionnalités de Twig.
     * Cette méthode est spécifique à l'adaptateur Twig et permet d'ajouter
     * des filtres, des fonctions ou des tests personnalisés.
     *
     * @param ExtensionInterface $extension L'instance de l'extension Twig à ajouter.
     */
    public function addExtension(ExtensionInterface $extension): void
    {
        $this->twig->addExtension($extension);
    }
}
