<?php

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->getFinder()
    ->exclude('Build')
    ->exclude('vendor')
    ->exclude('Quality-Tools')
    ->in(__DIR__ );
return $config;
