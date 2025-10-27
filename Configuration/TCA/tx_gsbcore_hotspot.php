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
        'iconfile' => 'EXT:gsb_core/Resources/Public/Images/Icons/Imagemap.svg',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'hideTable' => true,
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'columns' => [
        'tooltip' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_hotspot.tooltip',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
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
        ]
    ],
    'types' => [
        '0' => ['showitem' =>
            '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, tooltip, imagemap,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime
        '],
    ],
];
