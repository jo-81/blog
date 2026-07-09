<?php

declare(strict_types=1);

namespace Framework\Exceptions;

class HttpException extends \Exception
{
    public function __construct(string $message = '', int $statusCode = 500, ?\Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->getCode();
    }
}
