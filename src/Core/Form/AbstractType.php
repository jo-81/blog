<?php

namespace Blog\Core\Form;

abstract class AbstractType implements FormTypeInterface
{
    public function render(string $name, string $label, array $fields = []): string
    {
        $html = "<div>\n";
        $html .= "<label for=\"{$name}\">{$label}</label>\n";
        $html .= "<input type=\"{$this->getType()}\" name=\"{$name}\" id=\"{$name}\"";

        foreach ($fields['options'] as $key => $value) { /** @phpstan-ignore-line */
            $html .= " {$key}=\"{$value}\""; /** @phpstan-ignore-line */
        }

        $html .= ">\n";
        $html .= "</div>\n";

        return $html;
    }
}
