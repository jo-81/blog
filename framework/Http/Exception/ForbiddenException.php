<?php

declare(strict_types=1);

namespace Framework\Http\Exception;

class ForbiddenException extends HttpException
{
    protected $message = "Vous n'avez pas les droits nécessaires pour accéder à cette ressource.";

    public function getStatusCode(): int
    {
        return 403;
    }
}
