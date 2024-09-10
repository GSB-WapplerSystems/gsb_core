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

use ITZBund\GsbCore\Configuration\PackageHelper;
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= ',
    ,--div--;GSB,sitePackage, navType, google_site_verification, copyright, --palette--;;logos, --palette--;;favicon, --palette--;;color,--palette--;;color-general
';

$GLOBALS['SiteConfiguration']['site']['palettes']['favicon']['label'] = 'Favicons';
$GLOBALS['SiteConfiguration']['site']['palettes']['favicon']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.favicon.palette.description';
$GLOBALS['SiteConfiguration']['site']['palettes']['favicon']['showitem'] = 'favicon-16x16,favicon-32x32,apple-touch-icon-60x60,apple-touch-icon-76x76,apple-touch-icon-120x120,apple-touch-icon-152x152,apple-touch-icon-180x180,safari-pinned-tab,shortcut-icon,webmanifest,browserconfig';

$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['label'] = 'Logos';
$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.logos.palette.description';
$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['showitem'] = 'logo-complete-toggle,second-logo-complete-toggle, logo-text, logo-complete-big, logo-complete-small, second-logo, second-logo-alt, second-logo-link';

$GLOBALS['SiteConfiguration']['site']['palettes']['color']['label'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.palette.color.label';
$GLOBALS['SiteConfiguration']['site']['palettes']['color']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.palette.color.description';
$GLOBALS['SiteConfiguration']['site']['palettes']['color']['showitem'] = 'color_1, label_color_1, color_2, label_color_2, color_3, label_color_3, color_4, label_color_4, color_5, label_color_5, color_6, label_color_6';

if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-2877')) {
    $GLOBALS['SiteConfiguration']['site']['palettes']['color-general']['label'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.palette.color-general.label';
    $GLOBALS['SiteConfiguration']['site']['palettes']['color-general']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.palette.color-general.description';
    $GLOBALS['SiteConfiguration']['site']['palettes']['color-general']['showitem'] = 'color_primary,color_secondary,color_secondary_rgba,color_tertiary,color_quaternary';
}

$GLOBALS['SiteConfiguration']['site']['columns']['sitePackage'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.description',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'itemsProcFunc' => PackageHelper::class . '->getSiteListForSiteModule',
    ],
];

// ITZBUNDPHP-2873 Copyright-Text
$GLOBALS['SiteConfiguration']['site']['columns']['copyright'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.copyright',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.copyright.description',
    'config' => [
        'type' => 'text',
        'renderType' => 'input',
    ],
];

if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-3176')) {
    $GLOBALS['SiteConfiguration']['site']['columns']['google_site_verification'] = [
        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.google_site_verification',
        'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.google_site_verification.description',
        'config' => [
            'type' => 'text',
            'renderType' => 'input',
        ],
    ];
}

// ITZBUNDPHP-2869 Logo Textmarke
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

//ITZBUNDPHP-2903 Logo komplett austauschen
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
    'displayCond' => 'FIELD:logo-complete-toggle:REQ:true',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];
$GLOBALS['SiteConfiguration']['site']['columns']['logo-complete-small'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.logo-complete-small',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.logo-complete-small',
    'displayCond' => 'FIELD:logo-complete-toggle:REQ:true',
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
    'displayCond' => 'FIELD:second-logo-complete-toggle:REQ:true',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];
$GLOBALS['SiteConfiguration']['site']['columns']['second-logo-alt'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.second-logo-alt',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.second-logo-alt',
    'displayCond' => 'FIELD:second-logo-complete-toggle:REQ:true',
    'config' =>
        [
            'type' => 'text',
            'renderType' => 'input',
        ],
];
$GLOBALS['SiteConfiguration']['site']['columns']['second-logo-link'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.second-logo-link',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.second-logo-link',
    'displayCond' => 'FIELD:second-logo-complete-toggle:REQ:true',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['url'],
        ],
];

// favicons

$GLOBALS['SiteConfiguration']['site']['columns']['favicon-16x16'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.favicon-16x16',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.favicon-16x16',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['favicon-32x32'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.favicon-32x32',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.favicon-32x32',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['apple-touch-icon-60x60'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.apple-touch-icon-60x60',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.apple-touch-icon-60x60',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['apple-touch-icon-76x76'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.apple-touch-icon-76x76',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.apple-touch-icon-76x76',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['apple-touch-icon-120x120'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.apple-touch-icon-120x120',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.apple-touch-icon-120x120',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];
$GLOBALS['SiteConfiguration']['site']['columns']['apple-touch-icon-152x152'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.apple-touch-icon-152x152',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.apple-touch-icon-152x152',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['apple-touch-icon-180x180'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.apple-touch-icon-180x180',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.apple-touch-icon-180x180',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['safari-pinned-tab'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.safari-pinned-tab',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.safari-pinned-tab',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['shortcut-icon'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.shortcut-icon',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.shortcut-icon',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['webmanifest'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.webmanifest',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.webmanifest',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['browserconfig'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.browserconfig',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.browserconfig',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['navType'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.nav-type',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.nav-type',
    'config' =>
        [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                [
                    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.items.nav-type.0',
                    'value' => 0,
                ],
                [
                    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.items.nav-type.1',
                    'value' => 1,
                ],
            ],
        ],
];

// add more color fields
for ($i = 0; $i <= 6; $i++) {
    $GLOBALS['SiteConfiguration']['site']['columns']["label_color_{$i}"] = [
        'label' => "LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_{$i}_label.label",
        'description' => "LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_{$i}_label.description",
        'config' => [
            'type' => 'input',
            'size' => 25,
        ],
    ];
    $GLOBALS['SiteConfiguration']['site']['columns']["color_{$i}"] = [
        'label' => "LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_{$i}.label",
        'description' => "LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_{$i}.description",
        'config' => [
            'type' => 'input',
            'size' => 25,
        ],
    ];
}

if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-2877')) {
    //    ITZBUNDPHP-2877 Color: Generell
    $GLOBALS['SiteConfiguration']['site']['columns']['color_primary'] = [
        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_primary.label',
        'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_primary.description',
        'config' => [
            'type' => 'input',
            'size' => 25,
        ],
    ];

    $GLOBALS['SiteConfiguration']['site']['columns']['color_secondary'] = [
        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_secondary.label',
        'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_secondary.description',
        'config' => [
            'type' => 'input',
            'size' => 25,
        ],
    ];

    $GLOBALS['SiteConfiguration']['site']['columns']['color_secondary_rgba'] = [
        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_secondary_rgba.label',
        'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_secondary_rgba.description',
        'config' => [
            'type' => 'input',
            'size' => 25,
        ],
    ];

    $GLOBALS['SiteConfiguration']['site']['columns']['color_tertiary'] = [
        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_tertiary.label',
        'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_tertiary.description',
        'config' => [
            'type' => 'input',
            'size' => 25,
        ],
    ];

    $GLOBALS['SiteConfiguration']['site']['columns']['color_quaternary'] = [
        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_quaternary.label',
        'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_quaternary.description',
        'config' => [
            'type' => 'input',
            'size' => 25,
        ],
    ];
}

if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-3288')) {
    $localizableKeys = [
        'logo-complete-toggle',
        'second-logo-complete-toggle',
        'logo-complete-big',
        'logo-complete-small',
        'second-logo',
        'second-logo-alt',
        'second-logo-link',
        'initiative-text-toggle',
        'initiative-text',
        'logo-text',
        'copyright',
    ];

    $GLOBALS['SiteConfiguration']['site']['columns']['initiative-text-toggle'] = [
        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.initiative-text-toggle.label',
        'displayCond' => 'FIELD:second-logo-complete-toggle:REQ:true',
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
        'displayCond' => [
            'AND' => [
                'FIELD:second-logo-complete-toggle:REQ:true',
                'FIELD:initiative-text-toggle:REQ:true',
            ],
        ],
        'config' => [
            'type' => 'text',
            'renderType' => 'input',
        ],
    ];

    $GLOBALS['SiteConfiguration']['site']['palettes']['logos']['showitem'] .= ', initiative-text-toggle, initiative-text';

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
}
