<?php

declare(strict_types=1);
$rootDir = \dirname(__DIR__, 4);
$classDir = "$rootDir/src/";
$rulesString = \file_get_contents(__DIR__ . '/php_cs.dist.json');
$rulesArray = \json_decode($rulesString, true);

if (isset($rulesArray['fixers']) && \is_array($rulesArray['fixers']) && \count($rulesArray['fixers'])) {
    $config = new \PhpCsFixer\Config();
    $config->setRiskyAllowed(true)
        ->setRules($rulesArray['fixers'])
        ->setFinder(
            PhpCsFixer\Finder::create()->exclude('vendor')
                ->in($classDir)
        );

    return $config;
}
