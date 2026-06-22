<?php

namespace Framework\Factories;

use Relay\Relay;
use Psr\Container\ContainerInterface;
use Framework\Adapters\RelayHttpPipeline;
use Framework\Http\HttpPipelineInterface;

/**
 * Usine de fabrication pour l'interface HttpPipelineInterface.
 *
 * Valide la configuration requise, résout dynamiquement la liste des
 * middlewares depuis le conteneur PSR-11 et assemble l'adaptateur final.
 */
class HttpPipelineFactory
{
    /**
     * Instancie et configure le pipeline de middlewares HTTP.
     *
     * @param ContainerInterface $container Le conteneur de dépendances PSR-11.
     * @return HttpPipelineInterface L'adaptateur de pipeline prêt à l'emploi.
     * @throws \RuntimeException Si la clé de configuration est manquante ou invalide.
     */
    public function __invoke(ContainerInterface $container): HttpPipelineInterface
    {
        // 1. Validation de l'existence de la clé de configuration
        if (!$container->has('app.middlewares')) {
            throw new \RuntimeException(
                "Erreur de configuration critique : La clé 'app.middlewares' est manquante dans votre configuration système."
            );
        }

        /** @var mixed $middlewareClasses */
        $middlewareClasses = $container->get('app.middlewares');

        // 2. Validation du type de données (Sécurise le array_map)
        if (!is_array($middlewareClasses)) {
            throw new \RuntimeException(
                "Erreur de configuration : La clé 'app.middlewares' doit obligatoirement être un tableau (array)."
            );
        }

        /** @var array<string> $middlewareClasses */

        // 3. Résolution dynamique de chaque middleware via le conteneur PSR-11
        $middlewares = array_map(function (string $middlewareClass) use ($container) {
            return $container->get($middlewareClass);
        }, $middlewareClasses);

        // 4. Assemblage de l'infrastructure
        $relay = new Relay($middlewares);
    
        return new RelayHttpPipeline($relay);
    }
}