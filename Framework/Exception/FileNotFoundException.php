<?php

declare(strict_types=1);

namespace Framework\Exception;

final class FileNotFoundException extends \RuntimeException
{
    public function __construct(string $fileName)
    {
        parent::__construct(\sprintf("Le fichier %s n'existe pas.", $fileName));
    }
}
