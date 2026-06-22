<?php

declare(strict_types=1);

namespace Framework\Factories;

use RuntimeException;
use Framework\Http\Router\Route;
use Psr\Container\ContainerInterface;
use Framework\Adapters\FastRouteRouter;
use Framework\Http\Router\RouterInterface;

/**
 * Class RouterFactory
 *
 * Factory chargée d'instancier le routeur de l'application via le conteneur de dépendances (PHP-DI).
 * Elle extrait et valide la configuration des routes avant d'injecter l'adaptateur concret.
 *
 * @package App\Factories
 */
class RouterFactory
{
    /**
     * Instancie et configure le routeur de l'application.
     *
     * @param ContainerInterface $container Le conteneur de dépendances.
     * @return RouterInterface L'instance du routeur prête à l'emploi.
     * @throws RuntimeException Si la configuration des routes est absente ou invalide.
     */
    public function __invoke(ContainerInterface $container): RouterInterface
    {
        // Barrière de sécurité : Vérification de l'existence de la clé de configuration
        if (!$container->has('app.routes')) {
            throw new RuntimeException(
                "Erreur de configuration critique : La clé 'app.routes' est manquante dans votre configuration système.",
            );
        }

        $routes = $container->get('app.routes');

        // Barrière de sécurité : Validation du type de données attendu
        if (!is_array($routes)) {
            throw new RuntimeException(
                "Erreur de configuration : La clé 'app.routes' doit obligatoirement être un tableau (array).",
            );
        }

        /** @var array<Route> $routes Informer l'analyseur statique du type contenu dans le tableau */

        $routerAdapter = new FastRouteRouter();
        $routerAdapter->addCollection($routes);

        return $routerAdapter;
    }
}
