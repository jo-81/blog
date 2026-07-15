<?php

declare(strict_types=1);

namespace Framework\Renderer\TwigExtensions;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class FormTypeExtension extends AbstractExtension
{
    public function __construct(
        private Environment $twig,
        private string $directory,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('form_row', [$this, 'renderFormRow'], ['is_safe' => ['html']]),
        ];
    }

    public function renderFormRow(array $form, string $name, array $options = []): string
    {
        if (!isset($form['fields'][$name]['type'])) {
            throw new \RuntimeException("Le champ n'est pas défini ou la structure est incorrecte.");
        }

        $formType = $form['fields'][$name]['type'];
        $templatePath = rtrim($this->directory, '/') . '/' . $formType->getBlockName() . '.twig';

        try {
            return $this->twig->render($templatePath, [
                'name'    => $name,
                'error'   => $form['errors'][$name] ?? null,
                'value'   => $this->getValue($form['data'], $name),
                'options' => array_merge($form['fields'][$name]['options'], $options),
            ]);
        } catch (\Twig\Error\LoaderError $e) {
            throw new \RuntimeException(sprintf("Le widget de formulaire '%s' n'existe pas dans les dossiers Twig.", $templatePath), 0, $e);
        }
    }

    private function getValue(array|object $data, string $name): mixed
    {
        if (is_array($data)) {
            return $data[$name] ?? null;
        }

        $getter = 'get' . ucfirst($name);
        if (method_exists($data, $getter)) {
            return $data->$getter();
        }

        return null;
    }
}
