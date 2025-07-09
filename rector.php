<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes',
        __DIR__ . '/Configuration',
        __DIR__ . '/ext_localconf.php',
    ])
    ->withPhpVersion(PhpVersion::PHP_83)
    ->withSets([
        Typo3SetList::CODE_QUALITY,
        Typo3SetList::GENERAL,
        Typo3LevelSetList::UP_TO_TYPO3_13,
    ])
    ->withPHPStanConfigs([Typo3Option::PHPSTAN_FOR_RECTOR_PATH])
    ->withSkip([
        __DIR__ . 'Configuration/ExtensionBuilder/*',
        __DIR__ . '/vendor/*',
        __DIR__ . '/Build/*',
        __DIR__ . '/Quality-Tools/*',
        __DIR__ . '/.github/*',
        NameImportingPostRector::class => [
            'ClassAliasMap.php',
        ],
    ])
;
