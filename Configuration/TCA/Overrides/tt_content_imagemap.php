<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2025 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund.
 * Developed by sitegeist media solutions GmbH (https://www.sitegeist.de)
 * Developer: Martin Neumann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['gsb_imagemap'] = 'tx_imagemap';
    $GLOBALS['TCA']['tt_content']['ctrl']['rootLevel'] = -1;
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
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:hotspot.description',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_gsbcore_hotspot',
                'foreign_field' => 'imagemap',
                'appearance' => [
                    'expandSingle' => true,
                    'newRecordLinkTitle' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:hotspot.new',
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
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);

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
                    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:gsb_imagemap.image',
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
                    image, hotspot,
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
