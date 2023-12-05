<?php

/*
 * This file is part of TYPO3 CMS-based extension "gsb_core".
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

$GLOBALS['SiteConfiguration']['site']['columns']['sitePackage'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.description',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'itemsProcFunc' => \ITZBund\GsbCore\Configuration\PackageHelper::class . '->getSiteListForSiteModule',
    ],
];

// Build searchbox toggle
$GLOBALS['SiteConfiguration']['site']['columns']['search'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.search',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.search.description',
    'config' => [
        'renderType' => 'checkboxToggle',
        'type' => 'check',
        'default' => 0,
    ],
];

$GLOBALS['SiteConfiguration']['site']['palettes']['default']['showitem'] .= ',
    sitePackage, search
';
