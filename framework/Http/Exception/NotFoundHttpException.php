<?php

declare(strict_types=1);

namespace Framework\Http\Exception;

class NotFoundHttpException extends HttpException
{
    protected $message = 'Page non trouvée.';

    public function getStatusCode(): int
    {
        return 404;
    }
}
