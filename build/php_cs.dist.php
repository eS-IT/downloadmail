<?php
$classDir       = __DIR__ . '/../Classes/';
$rulesString    = file_get_contents(__DIR__ . '/php_cs.dist.json');
$rulesArray     = json_decode($rulesString, true);

if (isset($rulesArray['fixers']) && is_array($rulesArray['fixers']) && count($rulesArray['fixers'])) {
    return PhpCsFixer\Config::create()
        ->setRiskyAllowed(true)
        ->setRules($rulesArray['fixers'])
        ->setFinder(
            PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in($classDir)
        );
}
