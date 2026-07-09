<?php

declare(strict_types=1);

namespace Framework\Form;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Validation\Constraint\ConstraintInterface;

class Form implements FormInterface
{
    private array $fields = [];

    private bool $isSubmitted = false;

    private array $errors = [];

    private array $attributes = [];

    public function __construct(
        private array $configuredFields,
        private mixed $data = null,
        private array $options = [],
    ) {
        $this->fields = $configuredFields;
        if (null === $this->data) {
            $this->data = [];
        }

        $this->attributes = array_merge([
            'method' => 'POST',
            'action' => '',
        ], $options['attr'] ?? []);

        $this->injectInitialData();
    }

    /**
     * Injecte les valeurs initiales de l'entité/tableau dans les champs
     */
    private function injectInitialData(): void
    {
        if (null === $this->data) {
            return;
        }

        foreach ($this->fields as $name => &$config) {
            if (is_array($this->data) && isset($this->data[$name])) {
                $config['options']['value'] = $this->data[$name];
            } elseif (is_object($this->data)) {
                // On cherche un getter (ex: getEmail())
                $getter = 'get' . ucfirst($name);
                if (method_exists($this->data, $getter)) {
                    $config['options']['value'] = $this->data->$getter();
                }
            }
        }
    }

    /**
     * Intercepte les données à partir de l'objet Request PSR-7
     */
    public function handleRequest(ServerRequestInterface $request): self
    {
        // 1. On récupère les données du corps de la requête (l'équivalent de $_POST)
        // getParsedBody() renvoie un array ou un objet (généralement un array pour les formulaires HTML)
        $requestData = $request->getParsedBody();

        if (!is_array($requestData)) {
            return $this;
        }

        // 2. On vérifie si un de nos champs est présent pour marquer le formulaire comme soumis
        foreach ($this->fields as $name => $config) {
            if (array_key_exists($name, $requestData)) {
                $this->isSubmitted = true;
                break;
            }
        }

        // 3. Si soumis, on valide et on hydrate
        if ($this->isSubmitted) {
            foreach ($this->fields as $name => &$config) {
                $value = $requestData[$name] ?? null;
                $config['options']['value'] = $value;

                // Hydratation de ton entité
                $this->hydrateData($name, $value);

                $this->validateField($name, $value, $config['options']['constraints'] ?? []);
            }
        }

        return $this;
    }

    /**
     * Parcourt et exécute toutes les contraintes attachées à un champ
     */
    private function validateField(string $fieldName, mixed $value, array $constraints): void
    {
        foreach ($constraints as $constraint) {
            if ($constraint instanceof ConstraintInterface) {
                $result = $constraint->validate($value);

                if (is_string($result)) {
                    $this->errors[$fieldName] = $result;
                    break;
                }
            }
        }
    }

    private function hydrateData(string $name, mixed $value): void
    {
        if (is_array($this->data)) {
            $this->data[$name] = $value;
        } elseif (is_object($this->data)) {
            $setter = 'set' . ucfirst($name);
            if (method_exists($this->data, $setter)) {
                $this->data->$setter($value);
            }
        }
    }

    public function isSubmitted(): bool
    {
        return $this->isSubmitted;
    }

    public function isValid(): bool
    {
        return $this->isSubmitted && empty($this->errors);
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function createView(): array
    {
        return [
            'fields'        => $this->fields,
            'errors'        => $this->errors,
            'isValid'       => $this->isValid(),
            'isSubmitted'   => $this->isSubmitted(),
            'attr'          => $this->attributes,
            'data'          => $this->data,
        ];
    }
}
