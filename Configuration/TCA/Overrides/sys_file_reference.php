<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Ole Hartwig <o.hartwig@moselwal.de> 2023
 * (c) Matthias Peltzer <matthias.peltzer@digitaspixelpark.com> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
        'is_accessible' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.is_accessible.label',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.is_accessible.description',
            'config' => [
                'renderType' => 'checkboxToggle',
                'type' => 'check',
                'placeholder' => '__row|uid_local|metadata|is_accessible',
                'mode' => 'useOrOverridePlaceholder',
                'default' => null,
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

    ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_reference',
        'basicoverlayPalette',
        'is_accessible',
        'after:title'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_reference',
        'imageoverlayPalette',
        'caption',
        'after:title'
    );
})();
