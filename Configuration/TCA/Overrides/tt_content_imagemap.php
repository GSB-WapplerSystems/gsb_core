<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['gsb_imagemap'] = 'tx_imagemap';

    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:gsb_imagemap.title',
            'gsb_imagemap',
            'tx_imagemap',
            'default',
        ]
    );

    $tempColumns = [
        'hotspot' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:hotspot',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_gsbcore_hotspot',
                'foreign_field' => 'imagemap',

                'appearance' => [
                    'expandSingle' => true,
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                ],
            ],
        ],
        'hotspot_popup' => [
            'label' => 'Hotspot Popup',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_gsbcore_hotspot',
            ],
        ],
        'color_default' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.color_default',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [],
                'itemsProcFunc' => ITZBund\GsbCore\UserFunc\ColorPickerValueItems::class . '->getItems',
            ],
        ],
        'color_active' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.color_active',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [],
                'itemsProcFunc' => ITZBund\GsbCore\UserFunc\ColorPickerValueItems::class . '->getItems',
            ],
        ],
        'color_hover' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.color_hover',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [],
                'itemsProcFunc' => ITZBund\GsbCore\UserFunc\ColorPickerValueItems::class . '->getItems',
            ],
        ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);

    $GLOBALS['TCA']['tt_content']['palettes'] += [
        'hotspotColor' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.colors',
            'showitem' => 'color_default,color_active,color_hover',
            'canNotCollapse' => 1,
        ]
    ];

    $GLOBALS['TCA']['tt_content']['types'] += [
        'gsb_imagemap' => [
            'columnsOverrides' => [
                'bodytext' => [
                    'config' => [
                        'richtextConfiguration' => 'default',
                        'enableRichtext' => 1,
                    ],
                ],
                'image' => [
                    'config' => [
                        'maxitems' => 1,
                        'minitems' => 1,
                        'allowed' => 'jpg,jpeg,svg,png,gif,webp',
                        'overrideChildTca' => [
                            'columns' => [
                                'link' => [
                                    'config' => [
                                        'type' => 'passthrough',
                                    ],
                                ],
                                'title' => [
                                    'config' => [
                                        'type' => 'passthrough',
                                    ],
                                ],
                                'outline' => [
                                    'config' => [
                                        'type' => 'passthrough',
                                        'renderType' => 'passthrough',
                                    ],
                                ],
                                'crop' => [
                                    'config' => [
                                        'type' => 'passthrough',
                                    ],
                                ],
                                'allow_download' => [
                                    'config' => [
                                        'renderType' => 'passthrough',
                                        'type' => 'passthrough',
                                    ],
                                ],
                                'caption' => [
                                    'config' => [
                                        'type' => 'passthrough',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header,
                    --palette--;;header_config,subheader,
                --div--;LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:gsb_imagemap.title,
                    image, --palette--;;hotspotColor, hotspot,
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
})();
