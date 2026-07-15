<?php

declare(strict_types=1);

namespace Framework\Http\Router;

class UrlGenerator
{
    public function __construct(private RouterInterface $router) {}

    public function generate(string $routeName, array $parameters = []): string
    {
        $routes = $this->router->getRoutes();

        if (!isset($routes[$routeName])) {
            throw new \InvalidArgumentException(sprintf('La route "%s" n\'existe pas.', $routeName));
        }

        $path = $routes[$routeName]->getPath(); // Ex: "/admin/tags/create/[/{id}]"

        // 1. On traite d'abord les segments optionnels entre crochets [...]
        // Cette regex cherche des crochets imbriquant éventuellement des placeholders, ex: "[/{id}]" ou "[/{id:\d+}]"
        $path = preg_replace_callback('/\[([^\]]+)\]/', function ($matches) use (&$parameters) {
            $subSegment = $matches[1]; // Ce qu'il y a dans les crochets, ex: "/{id}"

            // On cherche s'il y a un nom de paramètre à l'intérieur de ce segment, ex: "id"
            if (preg_match('/\{([a-zA-Z0-9_]+)(?::[^{}]+)?\}/', $subSegment, $placeholderMatches)) {
                $paramName = $placeholderMatches[1];

                // Si le paramètre optionnel N'EST PAS fourni, on supprime tout le bloc entre crochets !
                if (!array_key_exists($paramName, $parameters) || $parameters[$paramName] === null || $parameters[$paramName] === '') {
                    return '';
                }

                // Si le paramètre EST fourni, on remplace le placeholder par sa valeur
                $value = (string) $parameters[$paramName];
                unset($parameters[$paramName]); // Consommé, on l'enlève pour éviter la Query String

                // On remplace le placeholder (ex: {id} ou {id:\d+}) par sa valeur finale dans le sous-segment
                return preg_replace('/\{' . $paramName . '(?::[^{}]+)?\}/', $value, $subSegment);
            }

            return $subSegment;
        }, $path);

        // 2. On traite ensuite les paramètres OBLIGATOIRES restants (ceux qui ne sont pas entre crochets)
        $path = preg_replace_callback('/\{([a-zA-Z0-9_]+)(?::[^{}]+)?\}/', function ($matches) use (&$parameters, $routeName) {
            $paramName = $matches[1];

            if (!array_key_exists($paramName, $parameters)) {
                throw new \InvalidArgumentException(
                    sprintf('Le paramètre obligatoire "%s" est manquant pour la route "%s".', $paramName, $routeName),
                );
            }

            $value = (string) $parameters[$paramName];
            unset($parameters[$paramName]);

            return $value;
        }, $path);

        // 3. S'il reste des paramètres en trop, on les ajoute en Query String (?page=2)
        if (!empty($parameters)) {
            $path .= '?' . http_build_query($parameters);
        }

        return $path;
    }
}
