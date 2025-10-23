<?php

declare(strict_types=1);

namespace Framework\Module;

abstract class AbstractModule implements ModuleInterface
{
    public const DEFINITION = '';

    public static function getName(): string
    {
        $className = (new \ReflectionClass(static::class))->getShortName();
        $name = preg_replace('/(?<!^)([A-Z])/', '.$1', $className);

        return strtolower($name);
    }
}
