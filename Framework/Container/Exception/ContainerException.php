<?php

declare(strict_types=1);

namespace Framework\Container\Exception;

use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \RuntimeException implements ContainerExceptionInterface {}
