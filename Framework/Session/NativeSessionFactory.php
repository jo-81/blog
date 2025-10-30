<?php

declare(strict_types=1);

namespace Framework\Session;

use Framework\Trait\ContainerTrait;
use Psr\Container\ContainerInterface;
use Framework\Session\Interface\SessionInterface;

/**
 * Factory responsable de la création d'une instance NativeSession
 * configurée selon l'environnement d'exécution de l'application.
 *
 * Utilise l'environnement défini dans $_ENV['APP_ENV'] pour sélectionner
 * les options de session adaptées à cet environnement.
 *
 * Les options sont récupérées depuis le container via la clé "app.session_options".
 * En l'absence d'options spécifiques à l'environnement, les options 'dev' servent de fallback.
 *
 * @package Framework\Session
 */
class NativeSessionFactory
{
    use ContainerTrait;

    /**
     * Retourne une instance configurée de NativeSession selon l'environnement courant.
     *
     * @param ContainerInterface $container Le conteneur de dépendances pour accéder aux configurations.
     * @return SessionInterface Instance de session prête à l'emploi.
     */
    public function __invoke(ContainerInterface $container): SessionInterface
    {
        $env = $_ENV['APP_ENV'] ?? "dev";

        $options = $this->get($container, "app.session_options", []);
        $options = $options[$env] ?? $options['dev'];

        return new NativeSession($options);
    }
}
