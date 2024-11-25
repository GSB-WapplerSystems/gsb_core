<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $tempMenuSubpagesColumns = [
        'tx_subelements_header_style' => [
            'config' => [
                'items' =>
                    [
                        0 =>
                            [
                                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.menu_subpages.subelements_header_style.default',
                                'value' => '',
                            ],
                        1 =>
                            [
                                'label' => 'H3',
                                'value' => 'h3',
                            ],
                        2 =>
                            [
                                'label' => 'H4',
                                'value' => 'h4',
                            ],
                        3 =>
                            [
                                'label' => 'H5',
                                'value' => 'h5',
                            ],
                        4 =>
                            [
                                'label' => 'H6',
                                'value' => 'h6',
                            ],
                    ],
                'renderType' => 'selectSingle',
                'type' => 'select',
            ],
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.menu_subpages.subelements_header_style.label',
        ],
    ];

    $menuSubpagesAppearancePalettes = [
        'menu_subpages_items_appearance' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.menu_subpages.items.appearance',
            'showitem' => '--linebreak--,tx_subelements_header_style,grid_bgcolor',
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $menuSubpagesAppearancePalettes;

    $GLOBALS['TCA']['tt_content']['types']['menu_subpages']['columnsOverrides']['grid_bgcolor']['label'] = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.menu_subpages.items.appearance.grid_bgcolor.label';

    $GLOBALS['TCA']['tt_content']['types']['menu_subpages']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        --palette--;;headers, pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.menu_formlabel,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;;frames,
        --palette--;;menu_subpages_items_appearance,
        --palette--;;appearanceLinks, tx_dpnglossary_disable_parser,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.accessibility,
        --palette--;;menu_accessibility,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;;access,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories, categories,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes, rowDescription,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended
    ';

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $tempMenuSubpagesColumns
    );
})();
