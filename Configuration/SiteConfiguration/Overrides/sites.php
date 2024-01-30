<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Ole Hartwig <o.hartwig@moselwal.de> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
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
