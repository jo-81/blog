<?php

declare(strict_types=1);

namespace Framework\Form;

use Framework\Session\SessionInterface;
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
        private ?SessionInterface $session = null, // 🚀 Injection optionnelle de la session
    ) {
        $this->fields = $configuredFields;
        if (null === $this->data) {
            $this->data = [];
        }

        $this->attributes = array_merge([
            'method' => 'POST',
            'action' => '',
        ], $options['attr'] ?? []);

        // 1. Récupération préalable d'éventuelles erreurs et données flashées en session
        $this->loadFlashData();

        // 2. Injection des données (entité ou tableau)
        $this->injectInitialData();
    }

    /**
     * Charge les erreurs et les anciennes données stockées en session (Flash)
     */
    private function loadFlashData(): void
    {
        if (null === $this->session) {
            return;
        }

        // On récupère les données
        $flashErrors = $this->session->get('form_errors', []);
        $flashData = $this->session->get('form_old_data', []);

        if (!empty($flashErrors)) {
            $this->errors = $flashErrors;
            $this->session->remove('form_errors');
        }

        if (!empty($flashData)) {
            $this->data = $flashData;
            $this->session->remove('form_old_data');
        }
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
        $requestData = $request->getParsedBody();

        if (!is_array($requestData)) {
            return $this;
        }

        foreach ($this->fields as $name => $config) {
            if (array_key_exists($name, $requestData)) {
                $this->isSubmitted = true;
                break;
            }
        }

        if ($this->isSubmitted) {
            foreach ($this->fields as $name => &$config) {
                $value = $requestData[$name] ?? null;
                $config['options']['value'] = $value;

                $this->hydrateData($name, $value);
                $this->validateField($name, $value, $config['options']['constraints'] ?? []);
            }

            // 🚀 SAUVEGARDE EN FLASH SI ÉCHEC DE VALIDATION
            // Si le formulaire est soumis mais invalide, on sauvegarde pour la prochaine redirection.
            if (!$this->isValid()) {
                $this->saveFlashData();
            }
        }

        return $this;
    }

    /**
     * Enregistre les erreurs et les données soumises en session Flash
     */
    private function saveFlashData(): void
    {
        if (null === $this->session) {
            return;
        }

        $this->session->set('form_errors', $this->errors);

        // On sauvegarde l'état actuel sous forme de tableau
        $oldData = [];
        foreach ($this->fields as $name => $config) {
            $oldData[$name] = $config['options']['value'] ?? null;
        }
        $this->session->set('form_old_data', $oldData);
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
