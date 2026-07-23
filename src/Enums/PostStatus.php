<?php

declare(strict_types=1);

namespace App\Enums;

enum PostStatus: string
{
    case PUBLISHED = 'published';

    case DRAFT = 'draft';

    case PRIVATELY_PUBLISHED = 'private';

    case TRASHED = 'trash';

    /**
     * Retourne un libellé propre et lisible pour l'affichage (ex: dans Twig)
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::PRIVATELY_PUBLISHED => 'Privé',
            self::PUBLISHED => 'Publié',
            self::TRASHED => 'Corbeille',
        };
    }

    public static function has(?string $value): bool
    {
        if ($value === null) {
            return false;
        }

        return self::tryFrom($value) !== null;
    }

    public function toArray(): array
    {
        $choices = [];
        foreach (self::cases() as $status) {
            $choices[$status->value] = $status->getLabel();
        }
        return $choices;
    }
}
