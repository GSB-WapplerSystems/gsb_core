<?php

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->getFinder()->in(__DIR__ . '/_packages');
return $config;
