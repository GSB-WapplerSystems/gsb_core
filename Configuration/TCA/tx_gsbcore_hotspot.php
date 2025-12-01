<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

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
                'size' => 30,
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
            ['showitem' =>
                '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, tooltip, link, coordinates, popup, imagemap',
            ],
    ],
];
