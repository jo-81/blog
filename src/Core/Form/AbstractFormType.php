<?php

namespace Blog\Core\Form;

abstract class AbstractFormType implements FormInterface
{
    /** @var array<string, array<string, mixed>> */
    protected array $fields = [];

    protected string $method = "POST";

    protected string $action = "";

    /**
     * addField
     *
     * @param  string $name
     * @param  string $type
     * @param  string $label
     * @param  array<string, mixed> $options
     * @return void
     */
    public function addField(string $name, string $type, string $label, array $options = [])
    {
        $this->fields[$name] = [
            'type' => $type,
            'label' => $label,
            'options' => $options
        ];
    }

    public function render(): string
    {
        $html = "<form method=\"{$this->method}\" action=\"{$this->action}\">\n";

        foreach ($this->fields as $name => $fields) {

            if (! isset($fields['type'])) {
                throw new \Exception(sprintf("La clé type n'existe pas pour le champ %s", $name));
            }

            /** @var string */
            $formType = $fields['type'];
            if (! class_exists($formType)) {
                throw new \Exception(sprintf("La classe de formulaire %s n'existe pas", $formType));
            }

            /** @var AbstractType */
            $formTypeInstance = new $formType();

            /** @var string */
            $label = $fields['label'];
            $html .= $formTypeInstance->render($name, $label, $fields);
        }

        $html .= "<button type=\"submit\">Envoyer</button>\n";
        $html .= "</form>";

        return $html;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
