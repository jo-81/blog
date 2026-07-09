<?php

declare(strict_types=1);

namespace Framework\Factories;

use Framework\Session\PHPSession;
use Psr\Container\ContainerInterface;
use Framework\Session\SessionInterface;

class PHPSessionFactory
{
    public function __invoke(ContainerInterface $container): SessionInterface
    {
        $options = [
            'cookie_lifetime' => 0,
            'cookie_path' => '/',
            'cookie_secure' => false,
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
        ];

        if ($container->has('config.session')) {
            $config = $container->get('config.session');

            $options = is_array($config) ? array_merge($options, $config) : $options;
        }

        return new PHPSession($options);
    }
}
