<?php

declare(strict_types=1);

namespace Framework\Module;

abstract class AbstractModule implements ModuleInterface
{
    public const DEFINITION = null;

    public static function getName(): string
    {
        $classShortName = (new \ReflectionClass(static::class))->getShortName();

        $name = preg_replace([
            '/([A-Z]+)([A-Z][a-z])/',
            '/([a-z\d])([A-Z])/',
        ], '$1.$2', $classShortName);

        return strtolower($name ?? $classShortName);
    }
}
