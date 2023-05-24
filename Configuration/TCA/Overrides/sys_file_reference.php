<?php

declare(strict_types=1);

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
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newColumns);

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_reference',
        'imageoverlayPalette',
        'outline,allow_download',
        'after:title'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_reference',
        'basicoverlayPalette',
        'is_accessible',
        'after:title'
    );
})();
