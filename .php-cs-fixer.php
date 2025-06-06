<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP84Migration' => true,
        '@PHP82Migration:risky' => true,
        '@PER-CS2.0' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => null],
    ])
    ->setFinder($finder)
;
