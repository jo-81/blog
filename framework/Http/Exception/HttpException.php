<?php

declare(strict_types=1);

namespace Framework\Http\Exception;

abstract class HttpException extends \Exception
{
    abstract public function getStatusCode(): int;
}
