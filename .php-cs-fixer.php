<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    'binary_operator_spaces'             => [
        'default'   => 'single_space',
        'operators' => [
            '='  => 'align_single_space_minimal',
            '=>' => 'align_single_space_minimal',
        ],
    ],
    'array_syntax'                       => ['syntax' => 'short'],
    'indentation_type'                   => true,
    'line_ending'                        => true,
    'linebreak_after_opening_tag'        => true,
    'no_trailing_whitespace'             => true,
    'single_blank_line_at_eof'           => true,
    'blank_line_after_namespace'         => true,
    'blank_line_after_opening_tag'       => true,
    'no_extra_blank_lines'               => [
        'tokens' => ['extra', 'throw', 'use'],
    ],
    'no_blank_lines_after_class_opening' => true,
    'class_attributes_separation'        => [
        'elements' => ['method' => 'one', 'trait_import' => 'none'],
    ],
    'statement_indentation'              => true,
    'array_indentation'                  => true,
    'method_argument_space'              => true,
    'no_spaces_after_function_name'      => true,
    'no_spaces_around_offset'            => true,
    'spaces_inside_parentheses'          => true,
    'no_trailing_comma_in_singleline'    => true,
    'trailing_comma_in_multiline'        => true,
    'whitespace_after_comma_in_array'    => true,
    'trim_array_spaces'                  => true,
    'unary_operator_spaces'              => true,
    'ternary_operator_spaces'            => true,
    'space_after_semicolon'              => true,
    'lowercase_keywords'                 => true,
    'constant_case'                      => true,
    'ordered_imports'                    => ['sort_algorithm' => 'alpha'],
    'no_unused_imports'                  => true,
    'visibility_required'                => [
        'elements' => ['method', 'property'],
    ],
    'function_declaration'               => true,
    'method_chaining_indentation'        => true,
    'control_structure_braces'           => true,
    'not_operator_with_successor_space'  => false,
    'object_operator_without_whitespace' => true,
    'single_quote'                       => true,
    'cast_spaces'                        => true,
    'short_scalar_cast'                  => true,
    'no_short_bool_cast'                 => true,
    'no_empty_statement'                 => true,
    'no_useless_return'                  => true,
    'phpdoc_indent'                      => true,
    'phpdoc_trim'                        => true,
    'phpdoc_no_useless_inheritdoc'       => true,
    'phpdoc_scalar'                      => true,
    'phpdoc_summary'                     => true,
    'phpdoc_types'                       => true,
    'ErickSkrauch/align_multiline_parameters' => [
        'variables' => true,
        'defaults'  => true,
    ],
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new Config();
return $config->setFinder($finder)
    ->registerCustomFixers(new \ErickSkrauch\PhpCsFixer\Fixers())
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setUnsupportedPhpVersionAllowed(true);
