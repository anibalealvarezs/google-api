<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
    ])
    ->setFinder(
        (new Finder())
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
            ->name('*.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    )
;
