<?php

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__.'/examples')
    ->in(__DIR__.'/library')
    ->in(__DIR__.'/tests')
;

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR2' => true,
    ])
    ->setFinder($finder)
;
