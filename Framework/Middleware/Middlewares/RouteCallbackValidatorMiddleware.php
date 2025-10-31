<?php

declare(strict_types=1);

namespace Framework\Middleware\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Framework\Exception\NotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Router\Interface\RouteInterface;
use Framework\Http\Interface\AppRequestInterface;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Router\Exception\RouteNotFoundException;
use Framework\Middleware\Exception\RouteCallbackValidatorException;

/**
 * Middleware qui valide et résout le callback de la route vers le contrôleur et sa méthode.
 *
 * Ce middleware vérifie la conformité du callback de chaque route
 * (chaîne au format 'ClassName@methodName'), s'assure que le contrôleur existe
 * dans le conteneur de dépendances et que sa méthode cible est publique et appelable.
 * Il stocke ensuite l'instance du contrôleur et la méthode comme attribut dans la requête pour le reste de la chaîne.
 *
 * @package Framework\Middleware\Middlewares
 */
final class RouteCallbackValidatorMiddleware implements MiddlewareInterface
{
    /**
     * Initialise le middleware avec le conteneur de dépendances.
     *
     * @param ContainerInterface $container Conteneur PSR pour les contrôleurs.
     */
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * Valide le callback du contrôleur de la route et injecte ses références dans la requête.
     *
     * @param AppRequestInterface $request Requête HTTP courante.
     * @param RequestHandlerInterface $handler Handler suivant dans la pile.
     *
     * @return AppResponseInterface Réponse HTTP retournée par le handler.
     *
     * @throws RouteNotFoundException Si aucune route n'est trouvée dans la requête.
     * @throws RouteCallbackValidatorException Si le callback n'est pas conforme ou inaccessible.
     * @throws NotFoundException Si le contrôleur ciblé est absent du conteneur.
     * @throws \RuntimeException Si la méthode ciblée est absente du contrôleur.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): AppResponseInterface
    {
        /** @var RouteInterface|null */
        $route = $request->getAttribute(RoutingMiddleware::ATTRIBUTE_NAME);
        if (null === $route) {
            throw new RouteNotFoundException();
        }

        $target = $route->getTarget();
        if (!str_contains($target, "@")) {
            throw new RouteCallbackValidatorException(
                "Le callback doit contenir le séparateur '@'."
            );
        }

        $targets = explode("@", $target, 2);
        if (count($targets) !== 2 || empty($targets[0]) || empty($targets[1])) {
            throw new RouteCallbackValidatorException(
                "Le callback doit être au format 'ClassName@methodName'."
            );
        }

        [$controllerClass, $method] = $targets;
        if (!$this->container->has($controllerClass)) {
            throw new NotFoundException(
                sprintf("Le controller '%s' n'existe pas.", $controllerClass)
            );
        }

        $controller = $this->container->get($controllerClass);
        if (!method_exists($controller, $method)) {
            throw new \RuntimeException(
                sprintf(
                    "La méthode '%s' n'existe pas pour le controller '%s'.",
                    $method,
                    $controllerClass
                )
            );
        }

        if (!is_callable([$controller, $method])) {
            throw new RouteCallbackValidatorException(
                sprintf(
                    "Le callback '%s::%s' n'est pas accessible (la méthode doit exister et être publique).",
                    $controllerClass,
                    $method
                )
            );
        }

        // Ajoute l'attribut contenant le contrôleur et la méthode validés
        $request = $request->withAttribute("_app__target", [$controller, $method]);

        /** @var AppResponseInterface */
        return $handler->handle($request);
    }
}
