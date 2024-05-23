<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $visibleoptions = [
        'newsletter' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.NewsletterBox',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.NewsletterBox.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.NewsletterBox',
                        'labelChecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
                        'labelUnchecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.disabled',
                    ],
                ],
            ],
        ],

        'socialmedia' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.SocialmediaBar',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.SocialmediaBar.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.SocialmediaBar',
                        'labelChecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
                        'labelUnchecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.disabled',
                    ],
                ],
            ],
        ],

        'breadcrumb' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.Breadcrumb',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.Breadcrumb.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.Breadcrumb',
                        'labelChecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
                        'labelUnchecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.disabled',
                    ],
                ],
            ],
        ],

        'teaser_description' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.TeaserDescription',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.TeaserDescription.description',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 10,
            ],
        ],
        'main_category' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.main_category',
            'config' => [
                'treeConfig' => [
                    'startingPoints' => '###SITE:categories.root###',
                ],
                'type' => 'category',
                'maxitems' => 1,
                'relationship' => 'oneToOne',
            ],
        ],
        'categories' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:pages.sub_category',
            'config' => [
                'treeConfig' => [
                    'startingPoints' => '###SITE:categories.root###',
                ],
                'type' => 'category',
            ],
        ],
    ];
    ExtensionManagementUtility::addTCAcolumns('pages', $visibleoptions);
    ExtensionManagementUtility::addFieldsToPalette('pages', 'layout', '--linebreak--,newsletter,socialmedia,breadcrumb', 'after:newUntil');
    ExtensionManagementUtility::addFieldsToPalette('pages', 'media', '--linebreak--,teaser_description', 'after:media');
    ExtensionManagementUtility::addToAllTCAtypes('pages', 'main_category', '', 'before:categories');
})();
