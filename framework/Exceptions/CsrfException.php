<?php

declare(strict_types=1);

namespace Framework\Exceptions;

class CsrfException extends HttpException
{
    public function __construct(string $message = 'Jeton expiré (Erreur CSRF).')
    {
        parent::__construct($message, 419);
    }
}
