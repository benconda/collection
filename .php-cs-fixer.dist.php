<?php

$config = new PhpCsFixer\Config();
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests');

$config->setRules([
    '@Symfony' => true
]);
return $config->setFinder($finder);