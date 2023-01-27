<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

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

        'highlight' => [
            'label' => 'Highlight Article',
            'config' => [
                'type' => 'check',
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

        'teaser_description_overview' => [
            'label' => 'Teaser Description Overview',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 10,
            ],
        ],

        'category_title' => [
            'label' => 'Category title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns('pages', $visibleoptions);
    ExtensionManagementUtility::addFieldsToPalette('pages', 'layout', '--linebreak--,newsletter,socialmedia,breadcrumb', 'after:newUntil');
    ExtensionManagementUtility::addFieldsToPalette('pages', 'title', '--linebreak--,category_title', 'after:subtitle');

    $event = [
        'event' => [
            'exclude' => 1,
            'onChange' => 'reload',
            'label' => 'Event Page',
            'description' => 'Toggle to an eventpage',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '0',
                        1 => '1',
                    ],
                ],
                'default' => '0',
            ],
        ],

        'event_startdate' => [
            'exclude' => 1,
            'label' => 'Beginn',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
            ],
            'displayCond' => 'FIELD:event:=:1',
        ],

        'event_enddate' => [
            'exclude' => 1,
            'label' => 'Ende',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
            'displayCond' => 'FIELD:event:=:1',
        ],

        'event_location' => [
            'exclude' => 1,
            'label' => 'Location',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'max' => 255,
            ],
            'displayCond' => 'FIELD:event:=:1',
        ],

        'event_past' => [
            'exclude' => 1,
            'label' => 'Vergangen',
            'description' => 'Vergangene Veranstaltung',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '0',
                        1 => '1',
                    ],
                ],
                'default' => '0',
            ],
            'displayCond' => 'FIELD:event:=:1',
        ],

        'event_category' => [
            'exclude' => 1,
            'label' => 'Kategorie (Optional)',
            'description' => 'Überschreibt die Kategorie Einstellung',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'max' => 255,
            ],
            'displayCond' => 'FIELD:event:=:1',
        ],

        'event_address' => [
            'exclude' => 1,
            'label' => 'Adresse',
            'config' => [
                'type' => 'text',
                'size' => 1024,
                'cols' => 80,
                'rows' => 5,
            ],
            'displayCond' => 'FIELD:event:=:1',
        ],

        'event_signup_link' => [
            'exclude' => 1,
            'label' => 'Link',
            'config' => [
                'type' => 'link',
            ],
            'displayCond' => 'FIELD:event:=:1',
        ],

        'event_signup_link_label' => [
            'exclude' => 1,
            'label' => 'Link Label',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'max' => 255,
                'default' => 'Anmelden',
            ],
            'displayCond' => 'FIELD:event:=:1',
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns('pages', $event);
    ExtensionManagementUtility::addToAllTCAtypes(
        'pages', // Table name
        '--palette--;Event page;event', // Field list to add
        '1', // List of specific types to add the field list to. (If empty, all type entries are affected)
        'before:target' // Insert fields before (default) or after one, or replace a field
    );

    $GLOBALS['TCA']['pages']['palettes']['event'] = [
        'showitem' => 'event,--linebreak--,event_startdate,event_enddate,--linebreak--,event_location,event_category,--linebreak--,event_address,event_past,--linebreak--,event_signup_link,event_signup_link_label',
    ];

    ExtensionManagementUtility::addFieldsToPalette('pages', 'media', '--linebreak--,highlight,--linebreak--,teaser_description,--linebreak--,teaser_description_overview', 'after:media');
})();
