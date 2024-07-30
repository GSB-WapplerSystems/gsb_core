<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $headerIntext = [
        'tx_header_inside' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_position.inside',
            'config' => [
                'type' => 'check',
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $headerIntext
    );

    $headerStyle = [
        'tx_header_style' => [
            'config' => [
                'items' =>
                    [
                        0 =>
                            [
                                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_style.default',
                                'value' => '',
                            ],
                        1 =>
                            [
                                'label' => 'H2',
                                'value' => 'h2',
                            ],
                        2 =>
                            [
                                'label' => 'H3',
                                'value' => 'h3',
                            ],
                        3 =>
                            [
                                'label' => 'H4',
                                'value' => 'h4',
                            ],
                        4 =>
                            [
                                'label' => 'H5',
                                'value' => 'h5',
                            ],
                        5 =>
                            [
                                'label' => 'H6',
                                'value' => 'h6',
                            ],
                    ],
                'renderType' => 'selectSingle',
                'type' => 'select',
            ],
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_style',
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $headerStyle
    );

    ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'headers',
        'tx_header_style,tx_header_inside',
        'after:header_position'
    );

    $headerPalettes = [
        'header_config' => [
            'showitem' => 'header_layout,header_position,tx_header_style,tx_header_inside', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $headerPalettes;
})();
