<?php

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
                                0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_style.default',
                                1 => '',
                            ],
                        1 =>
                            [
                                0 => 'H2',
                                1 => 'h2',
                            ],
                        2 =>
                            [
                                0 => 'H3',
                                1 => 'h3',
                            ],
                        3 =>
                            [
                                0 => 'H4',
                                1 => 'h4',
                            ],
                        4 =>
                            [
                                0 => 'H5',
                                1 => 'h5',
                            ],
                        5 =>
                            [
                                0 => 'H6',
                                1 => 'h6',
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

    $headerKicker = [
        'header_kicker' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_position.kicker',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $headerKicker
    );

    ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'headers',
        'header_kicker, --linebreak--',
        'before:header'
    );

    $headerPalettes = [
        'header_config' => [
            'showitem' => 'header_layout,header_position,tx_header_style,tx_header_inside', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $headerPalettes;
})();
