<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Christian Rath-Ulrich, Willi Wehmeier
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['label'] = 'Logos';
$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.logos.palette.description';
$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['showitem'] = 'logo-complete-toggle,second-logo-complete-toggle, logo-text, logo-complete-big, logo-complete-small, second-logo, second-logo-alt, second-logo-link';

$GLOBALS['SiteConfiguration']['site']['columns']['logo-text'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.logo-text',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.logo-text',
    'displayCond' => 'FIELD:logo-complete-toggle:REQ:false',

    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['logo-complete-toggle'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.logo-complete-toggle',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.logo-complete-toggle',
    'onChange' => 'reload',
    'config' => [
        'renderType' => 'checkboxToggle',
        'type' => 'check',
        'default' => 0,
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['logo-complete-big'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.logo-complete-big',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.logo-complete-big',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];
$GLOBALS['SiteConfiguration']['site']['columns']['logo-complete-small'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.logo-complete-small',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.logo-complete-small',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

//ITZBUNDPHP-2870 Zweites Logo
$GLOBALS['SiteConfiguration']['site']['columns']['second-logo-complete-toggle'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.second-logo-complete-toggle',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.second-logo-complete-toggle',
    'onChange' => 'reload',
    'config' => [
        'renderType' => 'checkboxToggle',
        'type' => 'check',
        'default' => 0,
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['second-logo'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.second-logo',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.second-logo',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];
$GLOBALS['SiteConfiguration']['site']['columns']['second-logo-alt'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.second-logo-alt',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.second-logo-alt',
    'config' =>
        [
            'type' => 'text',
            'renderType' => 'input',
        ],
];
$GLOBALS['SiteConfiguration']['site']['columns']['second-logo-link'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.second-logo-link',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.second-logo-link',

    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['url'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['initiative-text-toggle'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.initiative-text-toggle.label',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.initiative-text-toggle',
    'onChange' => 'reload',
    'config' => [
        'renderType' => 'checkboxToggle',
        'type' => 'check',
        'default' => 0,
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['initiative-text'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.initiative-text.label',
    'config' => [
        'type' => 'text',
        'renderType' => 'input',
    ],
];

$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['showitem'] .= ', initiative-text-toggle, initiative-text';

// ITZBUNDPHP-3288 Localizable site settings
$localizableKeys = [
    'logo-complete-big',
    'logo-complete-small',
    'logo-complete-toggle',
    'logo-text',
    'second-logo',
    'second-logo-alt',
    'second-logo-link',
    'initiative-text',
];

foreach ($localizableKeys as $localizableKey) {
    $GLOBALS['SiteConfiguration']['site_language']['columns'][$localizableKey] = $GLOBALS['SiteConfiguration']['site']['columns'][$localizableKey];

    if (str_contains($localizableKey, 'toggle')) {
        $GLOBALS['SiteConfiguration']['site_language']['columns'][$localizableKey]['config']['readOnly'] = true;
    } else {
        $GLOBALS['SiteConfiguration']['site_language']['columns'][$localizableKey]['config']['mode'] = 'useOrOverridePlaceholder';
        $GLOBALS['SiteConfiguration']['site_language']['columns'][$localizableKey]['config']['eval'] = 'null';
        $GLOBALS['SiteConfiguration']['site_language']['columns'][$localizableKey]['config']['default'] = null;
        $GLOBALS['SiteConfiguration']['site_language']['columns'][$localizableKey]['config']['nullable'] = true;
    }
}

$GLOBALS['SiteConfiguration']['site_language']['palettes']['localized-logos-and-copyright'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.palette.localized-logos-and-copyright.label',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.palette.localized-logos-and-copyright.description',
    'showitem' => implode(',', $localizableKeys),
];

$GLOBALS['SiteConfiguration']['site_language']['types']['1']['showitem'] .= ',--palette--;;localized-logos-and-copyright';
