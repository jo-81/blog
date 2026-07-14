<?php

declare(strict_types=1);

namespace Framework\Utils;

use Transliterator;

class Slugger
{
    /**
     * Génère un slug propre et URL-friendly à partir d'une chaîne de caractères.
     *
     * Exemple : "  Un super Tag, avec des accents (é, à, ç) ! " -> "un-super-tag-avec-des-accents-e-a-c"
     */
    public static function slugify(string $string, string $delimiter = '-'): string
    {
        // 1. On nettoie les espaces superflus au début et à la fin
        $string = trim($string);

        // 2. Translittération : remplace les caractères accentués ou spéciaux par leur équivalent ASCII
        // Exemple: "é" devient "e", "ü" devient "u", "œ" devient "oe", etc.
        if (class_exists(Transliterator::class)) {
            $transliterator = Transliterator::create('Any-Latin; Latin-ASCII;');
            if ($transliterator !== null) {
                $string = $transliterator->transliterate($string);
            }
        } else {
            // Solution de secours (fallback) si l'extension 'intl' n'est pas installée sur le serveur PHP
            $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        }

        // 3. On passe tout en minuscules
        $string = mb_strtolower($string, 'UTF-8');

        // 4. On remplace tout ce qui n'est pas une lettre, un chiffre ou un espace par un tiret
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);

        // 5. On remplace les espaces et les tirets multiples par notre délimiteur unique
        $string = preg_replace('/[\s-]+/', $delimiter, $string);

        // 6. Un dernier nettoyage des délimiteurs isolés aux extrémités (ex: "-mon-slug-" -> "mon-slug")
        return trim($string, $delimiter);
    }
}
