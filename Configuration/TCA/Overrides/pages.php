<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $visibleoptions = [
        'newsletter' => [
            'exclude' => 1,
            'label' => 'Newsletter Box',
            'description' => 'Remove newsletter box in the footer',
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
            'label' => 'Socialmedia Bar',
            'description' => 'Remove socialmedia bar',
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
            'label' => 'Breadcrumb',
            'description' => 'Remove breadcrumb',
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
            'label' => 'Teaser Description',
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
