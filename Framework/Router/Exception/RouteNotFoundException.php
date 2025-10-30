<?php

declare(strict_types=1);

namespace Framework\Router\Exception;

final class RouteNotFoundException extends \Exception
{
    public const HTTP_STATUS_CODE = 404;

    public function __construct(string $message = 'Aucune route de trouvée.')
    {
        parent::__construct($message, self::HTTP_STATUS_CODE);
    }
}
