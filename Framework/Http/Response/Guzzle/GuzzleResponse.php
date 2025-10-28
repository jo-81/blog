<?php

declare(strict_types=1);

namespace Framework\Http\Response\Guzzle;

use GuzzleHttp\Psr7\Response;
use Framework\Http\Interface\AppResponseInterface;

class GuzzleResponse extends Response implements AppResponseInterface
{
}
