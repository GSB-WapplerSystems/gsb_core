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

use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('GSB11_FEATURE_6050_IMAGEMAP')) {
    return [
        'ctrl' => [
            'title' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:hotspot',
            'label' => 'tooltip',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'delete' => 'deleted',
            'sortby' => 'sorting',
            'default_sortby' => 'tooltip',
            'versioningWS' => true,
            'rootLevel' => -1,
            'iconfile' => 'EXT:gsb_core/Resources/Public/Images/Icons/Hotspot.svg',
            'languageField' => 'sys_language_uid',
            'transOrigPointerField' => 'l10n_parent',
            'transOrigDiffSourceField' => 'l10n_diffsource',
            'translationSource' => 'l10n_source',
            'enablecolumns' => [
                'disabled' => 'hidden',
            ],
            'hideTable' => true,
            'security' => [
                'ignorePageTypeRestriction' => true,
            ],
        ],
        'columns' => [
            'tooltip' => [
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.tooltip',
                'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.tooltip.description',
                'config' => [
                    'type' => 'input',
                    'size' => 40,
                    'max' => 300,
                    'eval' => 'trim',
                ],
            ],
            'link' => [
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.link',
                'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.link.description',
                'config' => [
                    'type' => 'link',
                ],
            ],
            'coordinates' => [
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.coordinates',
                'config' => [
                    'type' => 'text',
                    'cols' => 10,
                    'placeholder' => 'Keine Koordinaten festgelegt',
                    'fieldControl' => [
                        'editHotspotControl' => [
                            'renderType' => 'editHotspotControl',
                        ],
                    ],
                ],
            ],
            'popup' => [
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.popup',
                'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.popup.description',
                'config' => [
                    'type' => 'inline',
                    'foreign_table' => 'tt_content',
                    'foreign_field' => 'hotspot_popup',
                    'appearance' => [
                        'collapseAll' => true,
                        'expandSingle' => true,
                        'newRecordLinkTitle' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.popup.new',
                        'showSynchronizationLink' => true,
                        'showAllLocalizationLink' => true,
                        'showPossibleLocalizationRecords' => true,
                    ],
                    'overrideChildTca' => [
                        'columns' => [
                            'CType' => [
                                'config' => [
                                    /* ToDo: this simply sets default. But it cant be overriden here in inline element to only show certain CTypes
                                    * so we will have to replace this by hook or event
                                    *                                'type' => 'passthrough',
                                    *                                'renderType' => '',
                                    */
                                    'default' => 'textpic',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'imagemap' => [
                'label' => 'Imagemap',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'foreign_table' => 'tt_content',
                    'foreign_table_where' => 'AND ctype="tx_gsb_imagemap"',
                ],
            ],
        ],
        'types' => [
            '0' =>
                [
                    'showitem' =>
                        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, tooltip, link, coordinates, popup, imagemap',
                ],
        ],
    ];
}
return [];
