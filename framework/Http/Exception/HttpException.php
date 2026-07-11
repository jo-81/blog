<?php

namespace Framework\Http\Exception;

abstract class HttpException extends \Exception 
{
    abstract public function getStatusCode(): int;
}