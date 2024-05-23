<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $linkColumns = [
        'tx_link' =>
            [
                'config' =>
                    [
                        'allowedTypes' => [
                            '0' => 'page',
                            '1' => 'file',
                            '2' => 'url',
                            '3' => 'record',
                        ],
                        'appearance' => [
                            'browserTitle' => 'Link',
                        ],
                        'type' => 'link',
                        'wizards' => [
                            'link' => [
                                'icon' => 'actions-wizard-link',
                            ],
                        ],
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link',
            ],
        'tx_link_layout' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_style.default',
                                        'value' => '',
                                    ],
                                1 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.0',
                                        'value' => 'btn btn-primary',
                                    ],
                                2 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.1',
                                        'value' => 'btn btn-secondary',
                                    ],
                                3 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.2',
                                        'value' => 'btn btn-tertiary',
                                    ],
                                4 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.3',
                                        'value' => 'btn btn-quaternary',
                                    ],
                                5 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.4',
                                        'value' => 'internal-link',
                                    ],
                                6 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.5',
                                        'value' => 'external-link',
                                    ],
                                7 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.6',
                                        'value' => 'download',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout',
            ],
        'tx_link_text' =>
            [
                'config' =>
                    [
                        'eval' => 'trim',
                        'type' => 'input',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_text',
            ],
        'tx_link_position' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_style.default',
                                        'value' => '',
                                    ],
                                1 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_position.I.0',
                                        'value' => 'btn-center',
                                    ],
                                2 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_position.I.1',
                                        'value' => 'btn-left',
                                    ],
                                3 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_position.I.2',
                                        'value' => 'btn-right',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_position',
            ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $linkColumns);

    $linkPalettes = [
        'link_config' => [
            'showitem' => 'tx_link_text,tx_link,tx_link_layout,tx_link_position', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $linkPalettes;
})();
