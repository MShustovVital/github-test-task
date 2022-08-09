<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var','vendor','templates']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'indentation_type' => true,
        'concat_space' => ['spacing' => 'one'],
        'single_blank_line_before_namespace' => true,
        'blank_line_after_namespace' => false,
        'not_operator_with_successor_space' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline', 'keep_multiple_spaces_after_comma' => false],
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'phpdoc_align' => ['align' => 'left'],
    ])
    ->setIndent("\t")
    ->setLineEnding("\n")
    ->setFinder($finder);