<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['include_element'] = 'tx_include_element';

    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:include_element.title',
            'include_element',
            'tx_include_element',
            'special',
        ]
    );

    $includeElementColumns = [
        'tx_include_rendering' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.rendering',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.rendering.iframe', 'iframe'],
                    ['LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.rendering.webcomponent', 'webcomponent'],
                ],
                'default' => 'iframe',
            ],
        ],
        'tx_include_url' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.url',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required,ITZBund\\GsbCore\\Evaluation\\HttpsUrlEvaluation',
                'placeholder' => 'https://www.bund.de',
            ],
        ],
        'tx_include_width' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.width',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '100%',
            ],
        ],
        'tx_include_height' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.height',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '400px',
            ],
        ],
        'tx_include_markup_component' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.markup_component',
            'displayCond' => 'FIELD:tx_include_rendering:=:webcomponent',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 20,
                'rows' => 5,
            ],
        ],
        'tx_include_html_title' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.html_title',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'tx_include_html_name' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.html_name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'tx_include_banner_image' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.include_element.banner_image',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
                'appearance' => [
                    'fileUploadAllowed' => true,
                    'elementBrowserEnabled' => true,
                ],
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns('tt_content', $includeElementColumns);

    $GLOBALS['TCA']['tt_content']['palettes'] += [
        'include_element' => [
            'showitem' => 'tx_include_rendering,--linebreak--,tx_include_url,--linebreak--,tx_include_width,tx_include_height,--linebreak--, tx_include_markup_component', 'canNotCollapse' => 1,
        ],
        'include_element_optional' => [
            'showitem' => 'tx_include_html_title,tx_include_html_name,--linebreak--,tx_include_banner_image',
        ],
    ];

    $GLOBALS['TCA']['tt_content']['types'] += [
        'include_element' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header,
                    --palette--;;header_config,subheader,
                    --palette--;;include_element,
                    --palette--;;include_element_optional,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;
                frames,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
        ],
    ];

    ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'special',
        'include_element'
    );

})();
