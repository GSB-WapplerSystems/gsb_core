<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Patrick Schriner <patrick.schriner@diemedialen.de> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

return [
    'ctrl' => [
        'title' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_consent',
        'label' => 'header',
        'crdate' => 'crdate',
        'default_sortby' => 'ORDER BY uid',
        'delete' => 'deleted',
        'languageField' => 'sys_language_uid',
        'origUid' => 't3_origuid',
        'searchFields' => 'header,body',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'translationSource' => 'l10n_source',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'transOrigPointerField' => 'l18n_parent',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'content-info',
        ],
        'versioningWS' => 1,
    ],
    'types' => [
        '0' => [
            'showitem' => 'header, show_accept, accept_button_label, body'],
    ],
    'columns' => [
        'header' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_consent.header',
            'config' => [
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'body' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_consent.body',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'show_accept' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_consent.show_accept',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 1,
                'items' => [
                    [
                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_consent.show_accept',
                        'labelChecked' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_consent.show_accept.show',
                        'labelUnchecked' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_consent.show_accept.hide',
                    ],
                ],
            ],
        ],
        'accept_button_label' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tx_gsbcore_consent.accept_button_label',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'sys_language_uid' => [
            'config' => [
                'type' => 'language',
            ],
        ],
    ],
];
