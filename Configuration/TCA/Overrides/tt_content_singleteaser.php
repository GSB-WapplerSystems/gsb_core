<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['gsb_template'] = 'tx_gsb_template';
    $tempColumns = [
        'tx_gsbsingleteaser_kicker' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser.kicker',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
            ],
        ],
        'tx_gsbsingleteaser_link' =>
            [
                'config' =>
                    [
                        'allowedTypes' => [
                            '0' => 'page',
                            '1' => 'file',
                            '2' => 'url',
                            '3' => 'record',
                            '4' => 'telephone',
                        ],
                        'appearance' => [
                            'browserTitle' => 'Link',
                        ],
                        'type' => 'link',
                        'wizards' => [
                            'link' => [
                                'icon' => 'actions-wizard-link',
                            ],
                        ],
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link',
            ],
        'tx_gsbsingleteaser_link_layout' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_layout.I.0',
                                        1 => 'btn btn-primary',
                                    ],
                                1 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_layout.I.1',
                                        1 => 'btn btn-secondary',
                                    ],
                                2 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_layout.I.2',
                                        1 => 'internal-link',
                                    ],
                                3 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_layout.I.3',
                                        1 => 'external-link',
                                    ],
                                4 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_layout.I.4',
                                        1 => 'download',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_layout',
            ],
        'tx_gsbsingleteaser_link_text' =>
            [
                'config' =>
                    [
                        'eval' => 'trim',
                        'type' => 'input',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_text',
            ],
        'tx_gsbsingleteaser_link_position' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_position.I.0',
                                        1 => 'btn-center',
                                    ],
                                1 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_position.I.1',
                                        1 => 'btn-left',
                                    ],
                                2 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_position.I.2',
                                        1 => 'btn-right',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbsingleteaser_link_position',
            ],
        'tx_gsbsingleteaser_event_startdate' => [
            'exclude' => 1,
            'label' => 'Begin',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
            ],
        ],
        'tx_gsbsingleteaser_event_enddate' => [
            'exclude' => 1,
            'label' => 'End',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
        ],
        'tx_gsbsingleteaser_place' =>
            [
                'config' =>
                    [
                        'eval' => 'trim',
                        'type' => 'input',
                    ],
                'exclude' => '1',
                'label' => 'Place',
            ],
        'tx_gsbsingleteaser_twitterlink' =>
            [
                'config' =>
                    [
                        'allowedTypes' => [
                            '0' => 'page',
                            '1' => 'file',
                            '2' => 'url',
                            '3' => 'record',
                            '4' => 'telephone',
                        ],
                        'appearance' => [
                            'browserTitle' => 'Link',
                        ],
                        'type' => 'link',
                        'wizards' => [
                            'link' => [
                                'icon' => 'actions-wizard-link',
                            ],
                        ],
                    ],
                'exclude' => '1',
                'label' => 'Twitter Link',
            ],
        'tx_gsbsingleteaser_twittertext' =>
            [
                'config' =>
                    [
                        'eval' => 'trim',
                        'type' => 'text',
                    ],
                'exclude' => '1',
                'label' => 'Twitter Text',
            ],
        'tx_gsbsingleteaser_twitterhashtag' =>
            [
                'config' =>
                    [
                        'eval' => 'trim',
                        'type' => 'input',
                    ],
                'exclude' => '1',
                'label' => 'Twitter Hashtags',
            ],
        'tx_gsbsingleteaser_twittervia' =>
            [
                'config' =>
                    [
                        'eval' => 'trim',
                        'type' => 'input',
                    ],
                'exclude' => '1',
                'label' => 'Twitter Via',
            ],
        'tx_gsbsingleteaser_twitterrelated' =>
            [
                'config' =>
                    [
                        'eval' => 'trim',
                        'type' => 'input',
                    ],
                'exclude' => '1',
                'label' => 'Twitter Related',
            ],

        'tx_gsbsingleteaser_bgimage' => [
            'config' =>
                [
                    'type' => 'file',
                    'appearance' =>
                        [
                            'useSortable' => 'tx_gsbsingleteaser_bgimage',
                            'headerThumbnail' =>
                                [
                                    'field' => 'uid_local',
                                    'width' => '45',
                                    'height' => '45c',
                                ],
                            'enabledControls' =>
                                [
                                    'info' => 'tx_gsbsingleteaser_bgimage',
                                    'new' => false,
                                    'dragdrop' => 'tx_gsbsingleteaser_bgimage',
                                    'sort' => false,
                                    'hide' => 'tx_gsbsingleteaser_bgimage',
                                    'delete' => 'tx_gsbsingleteaser_bgimage',
                                ],
                            'fileUploadAllowed' => '1',
                            'showAllLocalizationLink' => '1',
                            'showPossibleLocalizationRecords' => '1',
                            'showSynchronizationLink' => '1',
                        ],
                    'maxitems' => '1',
                    'minitems' => '0',
                ],
            'exclude' => '1',
            'label' => 'Background image',
        ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);

    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.CType.gsb_singleteaser',
            'gsb_singleteaser',
            'tx_gsb_singleteaser',
        ]
    );

    /*$datePalettes = [
        'date_config' => [
            'showitem' => 'tx_gsbsingleteaser_event_startdate,tx_gsbsingleteaser_event_enddate,tx_gsbsingleteaser_place,--linebreak--,tx_gsbsingleteaser_bgimage', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $datePalettes;*/

    $headerPalettes = [
        'header_config' => [
            'showitem' => 'header_layout,header_position,tx_header_style,--linebreak--,subheader,--linebreak--,header_link', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $headerPalettes;

    $linkPalettes = [
        'link_config' => [
            'showitem' => 'tx_gsbsingleteaser_link_text,tx_gsbsingleteaser_link,tx_gsbsingleteaser_link_layout,tx_gsbsingleteaser_link_position', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $linkPalettes;

    $imagePalettes = [
        'image_config' => [
            'showitem' => 'imageorient,imagewidth', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $imagePalettes;

    $twitterPalettes = [
        'twitter_config' => [
            'showitem' => 'tx_gsbsingleteaser_twittertext, tx_gsbsingleteaser_twitterlink, --linebreak--, tx_gsbsingleteaser_twitterhashtag, tx_gsbsingleteaser_twittervia, --linebreak--, tx_gsbsingleteaser_twitterrelated', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $twitterPalettes;

    $tempTypes = [
        'gsb_singleteaser' =>
            [
                'columnsOverrides' =>
                    [
                        'bodytext' =>
                            [
                                'config' =>
                                    [
                                        'richtextConfiguration' => 'default',
                                        'enableRichtext' => 1,
                                    ],
                            ],
                    ],
                'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
        tx_gsbsingleteaser_kicker,
        header,
        --palette--;;header_config,
        --palette--;;date_config,
        image,
        --palette--;;gallerySettings,
        bodytext,
        --palette--;;link_config,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;
        frames,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
        --div--;Social Media,
        --palette--;;twitter_config,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
            ],
    ];

    $GLOBALS['TCA']['tt_content']['types'] += $tempTypes;
})();
