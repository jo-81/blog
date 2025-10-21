<?php

$finder = PhpCsFixer\Finder::create()
    ->in(['src', 'Framework', 'tests'])
    ->exclude(['vendor', 'config'])
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'ordered_imports' => [
        'sort_algorithm' => 'length',
        'imports_order' => ['class', 'function', 'const'],
    ],
    'no_unused_imports' => true,
])->setFinder($finder)
;
