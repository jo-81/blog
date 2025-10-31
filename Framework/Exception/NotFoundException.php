<?php

declare(strict_types=1);

namespace Framework\Exception;

use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
}
