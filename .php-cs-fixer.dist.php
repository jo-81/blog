<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->notPath('config')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_before_statement' => true,
    'ordered_imports' => true,
    'no_unused_imports' => true,
])->setFinder($finder)
;
