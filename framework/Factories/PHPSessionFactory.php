<?php

namespace Framework\Factories;

use Framework\Session\PHPSession;
use Framework\Session\SessionInterface;
use Psr\Container\ContainerInterface;

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