<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['sys_file_reference']['columns']['description']['config']['enableRichtext'] = true;

    $newColumns = [
        'outline' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:sys_file_reference.outline',
            'config' => [
                'renderType' => 'checkboxToggle',
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'allow_download' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:sys_file_reference.allow_download',
            'config' => [
                'renderType' => 'checkboxToggle',
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'caption' => [
            'exclude' => true,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:sys_file_metadata.caption',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
            ],
        ],
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
    ];

    ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newColumns);

    ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_reference',
        'imageoverlayPalette',
        'outline,allow_download',
        'after:title'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_reference',
        'imageoverlayPalette',
        'caption',
        'after:title'
    );
    $GLOBALS['TCA']['sys_file_reference']['palettes']['videoOverlayPalette']['showitem'] = 'title';
})();
