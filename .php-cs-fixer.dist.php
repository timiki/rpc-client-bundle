<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src'
        ]);

return (new PhpCsFixer\Config)
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@Symfony' => true,
        '@DoctrineAnnotation' => true,
        'psr_autoloading' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'mb_str_functions' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'echo_tag_syntax' => false,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'strict_comparison' => true,
        'native_function_invocation' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_types_order' => true,
        'phpdoc_order' => true
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setUsingCache(false)
;