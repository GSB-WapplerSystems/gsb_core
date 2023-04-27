<?php

/*
 * This file is part of TYPO3 CMS-based extension "gsb_core".
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

$GLOBALS['SiteConfiguration']['site']['columns']['sitePackage'] = [
    'label' => 'Site package of this site',
    'description' => '[EXT:gsb_core] Attached site extension with TypoScript entry points',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'itemsProcFunc' => \ITZBund\GsbTemplate\Configuration\PackageHelper::class . '->getSiteListForSiteModule',
    ],
];
$GLOBALS['SiteConfiguration']['site']['palettes']['default']['showitem'] .= ',
    sitePackage,
';
