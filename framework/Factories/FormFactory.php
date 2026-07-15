<?php

declare(strict_types=1);

namespace Framework\Factories;

use Framework\Form\Form;
use Framework\Form\FormBuilder;
use Framework\Form\FormInterface;
use Psr\Container\ContainerInterface;
use Framework\Session\SessionInterface;
use Framework\Form\FormFactoryInterface;

class FormFactory implements FormFactoryInterface
{
    public function __construct(private ContainerInterface $container) {}

    public function create(string $formType, mixed $data = null, array $options = []): FormInterface
    {
        $typeInstance = $this->container->get($formType);
        $session = $this->container->get(SessionInterface::class);

        // 1. On crée un builder vierge
        $builder = new FormBuilder($data, $options);

        // 2. Le FormType remplit le builder avec ses chaînes de caractères
        $typeInstance->buildForm($builder);

        // 3. 🚀 LA CORRECTION : On récupère les champs et on résout les types de champs ici !
        $resolvedFields = $builder->getFields();

        foreach ($resolvedFields as $name => &$config) {
            // Si le type est une chaîne (ex: EmailType::class), on l'instancie via PHP-DI
            if (is_string($config['type'])) {
                $config['type'] = $this->container->get($config['type']);
            }
        }

        // 4. On passe les champs résolus à l'objet Form
        return new Form($resolvedFields, $data, $options, $session);
    }
}
