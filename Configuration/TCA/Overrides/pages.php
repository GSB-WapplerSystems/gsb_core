<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $visibleoptions = [
        'newsletter' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:pages.NewsletterBox',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:pages.NewsletterBox.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => 'enabled',
                        'labelChecked' => 'Enabled',
                        'labelUnchecked' => 'Disabled',
                    ],
                ],
            ],
        ],

        'socialmedia' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:pages.SocialmediaBar',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:pages.SocialmediaBar.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => 'enabled',
                        'labelChecked' => 'Enabled',
                        'labelUnchecked' => 'Disabled',
                    ],
                ],
            ],
        ],

        'breadcrumb' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:pages.Breadcrumb',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:pages.Breadcrumb.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => 'enabled',
                        'labelChecked' => 'Enabled',
                        'labelUnchecked' => 'Disabled',
                    ],
                ],
            ],
        ],

        'teaser_description' => [
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:pages.TeaserDescription',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:pages.TeaserDescription.description',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 10,
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns('pages', $visibleoptions);
    ExtensionManagementUtility::addFieldsToPalette('pages', 'layout', '--linebreak--,newsletter,socialmedia,breadcrumb', 'after:newUntil');
    ExtensionManagementUtility::addFieldsToPalette('pages', 'media', '--linebreak--,teaser_description', 'after:media');
})();
