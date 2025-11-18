<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['sys_file_metadata']['columns']['description']['config']['enableRichtext'] = true;

    $newColumns = [
        'is_accessible' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.is_accessible.label',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.is_accessible.description',
            'config' => [
                'renderType' => 'checkboxToggle',
                'type' => 'check',
                'default' => 0,
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_metadata', $newColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_metadata',
        '25',
        'is_accessible',
        'after:caption'
    );

    // add placeholder
    $fields = [
        'creator',
        'creator_tool',
        'publisher',
        'source',
        'copyright',
        'language',
        'location_country',
        'location_region',
        'location_city',
        'content_creation_date',
        'content_modification_date',
    ];

    $lll = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:sys_file_metadata';
    $fieldKey = 'placeholder';

    $columns = &$GLOBALS['TCA']['sys_file_metadata']['columns'];

    foreach ($fields as $field) {
        if (isset($columns[$field]['config'])) {
            $columns[$field]['config']['placeholder'] = $lll . '.' . $field . '.' . $fieldKey;
        }
    }

})();
