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
    ,--div--;GSB,sitePackage, show-copyright, navType, google_site_verification, copyright, sign-language-page, simple-language-page, --palette--;;logos, --palette--;;favicon, --palette--;;color,--palette--;;color-general,--palette--;;fonts
';

$GLOBALS['SiteConfiguration']['site']['palettes']['favicon']['label'] = 'Favicons';
$GLOBALS['SiteConfiguration']['site']['palettes']['favicon']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.favicon.palette.description';
$GLOBALS['SiteConfiguration']['site']['palettes']['favicon']['showitem'] = 'favicon-96x96-png, faviconIco, faviconSvg, apple-touch-icon, webmanifest, web-app-manifest-192x192, web-app-manifest-512x512';

$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['label'] = 'Logos';
$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.logos.palette.description';
$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['showitem'] = 'logo-complete-toggle,second-logo-complete-toggle, logo-text, logo-complete-big, logo-complete-small, second-logo, second-logo-alt, second-logo-link';

$GLOBALS['SiteConfiguration']['site']['palettes']['color']['label'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.palette.color.label';
$GLOBALS['SiteConfiguration']['site']['palettes']['color']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.palette.color.description';
$GLOBALS['SiteConfiguration']['site']['palettes']['color']['showitem'] = 'color_1, label_color_1, color_2, label_color_2, color_3, label_color_3, color_4, label_color_4, color_5, label_color_5, color_6, label_color_6';

$GLOBALS['SiteConfiguration']['site']['palettes']['color-general']['label'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.palette.color-general.label';
$GLOBALS['SiteConfiguration']['site']['palettes']['color-general']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sitepackage.palette.color-general.description';
$GLOBALS['SiteConfiguration']['site']['palettes']['color-general']['showitem'] = 'color_primary,color_secondary,color_secondary_rgba,color_tertiary,color_quaternary';

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

$GLOBALS['SiteConfiguration']['site']['columns']['google_site_verification'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.google_site_verification',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.google_site_verification.description',
    'config' => [
        'type' => 'text',
        'renderType' => 'input',
    ],
];

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

// Add different favicons to the page
$GLOBALS['SiteConfiguration']['site']['columns']['apple-touch-icon'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.apple-touch-icon',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.apple-touch-icon',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['faviconIco'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.faviconIco',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.faviconIco',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['faviconSvg'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.faviconSvg',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.faviconSvg',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['favicon-96x96-png'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.favicon-96x96-png',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.favicon-96x96-png',
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

$GLOBALS['SiteConfiguration']['site']['columns']['web-app-manifest-192x192'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.web-app-manifest-192x192',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.web-app-manifest-192x192',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['web-app-manifest-512x512'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.web-app-manifest-512x512',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.description.web-app-manifest-512x512',
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

$GLOBALS['SiteConfiguration']['site']['columns']['simple-language-page'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.simple-language-page.label',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.simple-language-page.description',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['page'],
            'size' => 50,
            'default' => '',
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['sign-language-page'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sign-language-page.label',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.sign-language-page.description',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['page'],
            'size' => 50,
            'default' => '',
        ],
];

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

$GLOBALS['SiteConfiguration']['site']['columns']['show-copyright'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.show-copyright.label',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.show-copyright.description',
    'config' => [
        'renderType' => 'checkboxToggle',
        'type' => 'check',
        'default' => 0,
    ],
];

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
        'show-copyright',
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

$GLOBALS['SiteConfiguration']['site']['columns']['display-brand-topline'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.display-brand-topline.label',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.display-brand-topline.description',
    'config' => [
        'renderType' => 'checkboxToggle',
        'type' => 'check',
        'default' => 0,
    ],
];

$GLOBALS['SiteConfiguration']['site']['palettes']['logos']['showitem'] .= ', display-brand-topline';

$GLOBALS['SiteConfiguration']['site']['palettes']['fonts']['label'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.fonts.palette.label';
$GLOBALS['SiteConfiguration']['site']['palettes']['fonts']['description'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.fonts.palette.description';
$GLOBALS['SiteConfiguration']['site']['palettes']['fonts']['showitem'] = 'font-switch, --linebreak--, font-sans-name, --linebreak--, font-sans, font-sans-italic, --linebreak--, font-sans-medium, --linebreak--, font-sans-bold, font-sans-bold-italic, --linebreak--, font-serif-name, --linebreak--, font-serif, font-serif-italic';
$GLOBALS['SiteConfiguration']['site']['columns']['font-switch'] = [
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-switch.label',
    'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-switch.description',
    'config' => [
        'renderType' => 'checkboxToggle',
        'type' => 'check',
        'default' => 0,
    ],
    'onChange' => 'reload',
];

$GLOBALS['SiteConfiguration']['site']['columns']['font-sans-name'] = [
    'displayCond' => 'FIELD:font-switch:REQ:true',
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-sans-name.label',
    'config' => [
        'type' => 'input',
        'size' => 50,
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['font-sans'] = [
    'displayCond' => 'FIELD:font-switch:REQ:true',
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-sans.label',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
            'size' => 50,
        ],
    'appearance' =>
        [
            'allowedExtensions' => ['woff2'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['font-sans-italic'] = [
    'displayCond' => 'FIELD:font-switch:REQ:true',
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-sans-italic.label',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
            'size' => 50,
        ],
    'appearance' =>
        [
            'allowedExtensions' => ['woff2'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['font-sans-medium'] = [
    'displayCond' => 'FIELD:font-switch:REQ:true',
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-sans-medium.label',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
            'size' => 50,
        ],
    'appearance' =>
        [
            'allowedExtensions' => ['woff2'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['font-sans-bold'] = [
    'displayCond' => 'FIELD:font-switch:REQ:true',
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-sans-bold.label',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
            'size' => 50,
        ],
    'appearance' =>
        [
            'allowedExtensions' => ['woff2'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['font-sans-bold-italic'] = [
    'displayCond' => 'FIELD:font-switch:REQ:true',
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-sans-bold-italic.label',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
            'size' => 50,
        ],
    'appearance' =>
        [
            'allowedExtensions' => ['woff2'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['font-serif-name'] = [
    'displayCond' => 'FIELD:font-switch:REQ:true',
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-serif-name.label',
    'config' => [
        'type' => 'input',
        'size' => 50,
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['font-serif'] = [
    'displayCond' => 'FIELD:font-switch:REQ:true',
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-serif.label',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
            'size' => 50,
        ],
    'appearance' =>
        [
            'allowedExtensions' => ['woff2'],
        ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['font-serif-italic'] = [
    'displayCond' => 'FIELD:font-switch:REQ:true',
    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.font-serif-italic.label',
    'config' =>
        [
            'type' => 'link',
            'allowedTypes' => ['file'],
            'size' => 50,
        ],
    'appearance' =>
        [
            'allowedExtensions' => ['woff2'],
        ],
];
