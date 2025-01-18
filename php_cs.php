<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->in(__DIR__.'/src')

    ->name('*.php')
    ->notPath('Fixtures');

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'binary_operator_spaces' => true,
    'blank_line_before_statement' => ['statements' => ['declare', 'return']],
    'cast_spaces' => ['space' => 'single'],
    'include' => true,
    'class_attributes_separation' => ['elements' => ['method' => 'one', 'trait_import' => 'none']],
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => true,
    'no_leading_namespace_whitespace' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_whitespace_in_blank_line' => true,
    'object_operator_without_whitespace' => true,
    'phpdoc_indent' => true,
    'no_empty_comment' => true,
    'standardize_not_equals' => true,
    'ternary_operator_spaces' => true,
    'trailing_comma_in_multiline' => ['elements' => ['arrays']],
    'unary_operator_spaces' => true,
    'no_unused_imports' => true,
    'fully_qualified_strict_types' => true,
    'single_line_after_imports' => true,
    'no_leading_import_slash' => true,
    'single_import_per_statement' => true,
])
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setFinder($finder);