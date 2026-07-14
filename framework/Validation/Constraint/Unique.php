<?php

declare(strict_types=1);

namespace Framework\Validation\Constraint;

class Unique implements ConstraintInterface
{
    /**
     * @param object $repository Le repository de ton entité (ex: UserRepository)
     * @param string $field Le nom de la colonne/propriété dans la base de données
     * @param mixed $ignoreId Optionnel : l'ID de l'entité actuelle pour l'exclure (utile en cas d'édition/UPDATE)
     * @param string $message Le message d'erreur personnalisé
     */
    public function __construct(
        private object $repository,
        private string $field = 'id',
        private mixed $ignoreId = null,
        private string $message = 'Cette valeur est déjà utilisée.',
    ) {}

    public function validate(mixed $value): bool|string
    {
        if (empty($value)) {
            return true; // Laisse la contrainte "Required" gérer le vide si besoin
        }

        // On cherche si un enregistrement existe déjà avec cette valeur
        // (Adapte cette méthode selon les conventions de ton ORM ou de tes Repositories, ex: findOneBy)
        $existingEntity = $this->repository->findOne([$this->field => $value]);

        if ($existingEntity) {
            // Cas particulier de l'ÉDITION :
            // Si l'enregistrement trouvé possède le même ID que l'entité qu'on modifie, c'est valide !
            if ($this->ignoreId !== null) {
                $getter = 'getId';
                if (method_exists($existingEntity, $getter) && $existingEntity->$getter() === $this->ignoreId) {
                    return true;
                }
            }

            return $this->message;
        }

        return true;
    }
}
