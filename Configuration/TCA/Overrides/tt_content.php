<?php

declare(strict_types=1);

defined('TYPO3') or die();

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

(static function (): void {
    $headerIntext = [
        'tx_header_inside' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.header_position.inside',
            'config' => [
                'type' => 'check',
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $headerIntext
    );

    $headerStyle = [
        'tx_header_style' => [
            'config' => [
                'items' =>
                    [
                        0 =>
                            [
                                0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.header_style.default',
                                1 => '',
                            ],
                        1 =>
                            [
                                0 => 'H2',
                                1 => 'h2',
                            ],
                        2 =>
                            [
                                0 => 'H3',
                                1 => 'h3',
                            ],
                        3 =>
                            [
                                0 => 'H4',
                                1 => 'h4',
                            ],
                        4 =>
                            [
                                0 => 'H5',
                                1 => 'h5',
                            ],
                        5 =>
                            [
                                0 => 'H6',
                                1 => 'h6',
                            ],
                    ],
                'renderType' => 'selectSingle',
                'type' => 'select',
            ],
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.header_style',
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $headerStyle
    );

    ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'headers',
        'tx_header_style,tx_header_inside',
        'after:header_position'
    );

    $headerKicker = [
        'header_kicker' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.header_position.kicker',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $headerKicker
    );

    ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'headers',
        'header_kicker, --linebreak--',
        'before:header'
    );

    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['video'] = 'actions-brand-youtube';

    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['audio'] = 'file-audio';

    $tempVideoColumns = [
        'tx_video_caption' =>
            [
                'config' =>
                    [
                        'allowedTypes' => [
                            '0' => 'file',
                            '1' => 'record',
                            '2' => 'telephone',
                        ],
                        'appearance' => [
                            'browserTitle' => 'Link',
                        ],
                        'type' => 'link',
                        'wizards' => [
                            'link' => [
                                'icon' => 'actions-wizards-link',
                                'params' => [
                                    'allowedExtensions' => 'vtt,rst',
                                ],
                            ],
                        ],
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_caption',
            ],
        'tx_video_poster_image' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'allowed' => 'jpg,jpeg,svg,png,gif',
                        'overrideChildTca' =>
                            [
                                'types' =>
                                    [
                                        0 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        1 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        2 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        3 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        4 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        5 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                    ],
                            ],
                        'appearance' =>
                            [
                                'useSortable' => 'tx_video_poster_image',
                                'headerThumbnail' =>
                                    [
                                        'field' => 'uid_local',
                                        'width' => '45',
                                        'height' => '45c',
                                    ],
                                'enabledControls' =>
                                    [
                                        'info' => 'tx_video_poster_image',
                                        'new' => false,
                                        'dragdrop' => 'tx_video_poster_image',
                                        'sort' => false,
                                        'hide' => 'tx_video_poster_image',
                                        'delete' => 'tx_video_poster_image',
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
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_poster_image',
            ],
        'tx_video_poster_video' =>
            [
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_poster_video_label',
                'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_poster_video_description',
                'config' => [
                    'type' => 'link',
                ],
            ],
        'tx_video_video' =>
            [
                'config' =>
                    [
                        'allowedTypes' => [
                            '0' => 'file',
                            '1' => 'record',
                            '2' => 'telephone',
                        ],
                        'appearance' => [
                            'browserTitle' => 'Link',
                        ],
                        'type' => 'link',
                        'wizards' => [
                            'link' => [
                                'icon' => 'actions-wizard-link',
                                'params' => [
                                    'allowedExtensions' => 'mp4,webm,ogg'
                                ]
                            ]
                        ]
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_video',
            ],
        'tx_video_videourl' =>
            [
                'config' =>
                    [
                        'allowedTypes' => [
                            '0' => 'url',
                            '1' => 'record',
                            '2' => 'telephone',
                        ],
                        'appearance' => [
                            'browserTitle' => 'link',
                        ],
                        'type' => 'link',
                        'wizards' => [
                            'link' => [
                                'icon' => 'actions-wizard-link'
                            ]
                        ]
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_videourl',
            ],
        'tx_video_mainstage' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_mainstage_label',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_mainstage_description',
            'config' => [
                'type' => 'check',
                'items' => [
                    ['LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_mainstage_checkbox', ''],
                ],
            ],
        ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempVideoColumns);

    $tempAudioColumns = [
        'tx_audio_poster' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'overrideChildTca' =>
                            [
                                'types' =>
                                    [
                                        0 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        1 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        2 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        3 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        4 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                        5 =>
                                            [
                                                'showitem' => '--palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette',
                                            ],
                                    ],
                            ],
                        'appearance' =>
                            [
                                'useSortable' => 'tx_audio_poster',
                                'headerThumbnail' =>
                                    [
                                        'field' => 'uid_local',
                                        'width' => '45',
                                        'height' => '45c',
                                    ],
                                'enabledControls' =>
                                    [
                                        'info' => 'tx_audio_poster',
                                        'new' => false,
                                        'dragdrop' => 'tx_audio_poster',
                                        'sort' => false,
                                        'hide' => 'tx_audio_poster',
                                        'delete' => 'tx_audio_poster',
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
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_audio_poster',
            ],
        'tx_audio_audio' =>
            [
                'config' =>
                    [
                        'allowedTypes' => [
                            '0' => 'file',
                            '1' => 'record',
                            '2' => 'telephone',
                        ],
                        'appearance' => [
                            'browserTitle' => 'Link',
                        ],
                        'type' => 'link',
                        'wizards' => [
                            'link' => [
                                'icon' => 'actions-wizard-link',
                                'params' => [
                                    'allowedExtensions' => 'mp3,wav'
                                ],
                            ],
                        ],
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_audio_audio',
            ],
        'tx_audio_audiourl' =>
            [
                'config' =>
                    [
                        'allowedTypes' => [
                            '0' => 'url',
                            '1' => 'record',
                            '2' => 'telephone',
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
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_audio_audiourl',
            ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempAudioColumns);

    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.CType.video',
            'video',
            'actions-brand-youtube',
        ]
    );

    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.CType.audio',
            'audio',
            'file-audio',
        ]
    );

    $headerVideoPalettes = [
        'headervideo_config' => [
            'showitem' => 'header_layout,header_position,tx_header_style', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $headerVideoPalettes;

    $headerAudioPalettes = [
        'headeraudio_config' => [
            'showitem' => 'header_layout,header_position,tx_header_style', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $headerAudioPalettes;

    $tempVideoTypes = [
        'video' =>
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
                'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,--palette--;;headervideo_config,subheader,bodytext,tx_video_mainstage,tx_video_video,tx_video_videourl,tx_video_poster_image,tx_video_poster_video,tx_video_caption,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,--palette--;;language,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,--palette--;;hidden,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,categories,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
            ],
    ];

    $tempAudioTypes = [
        'audio' =>
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
                'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,--palette--;;headeraudio_config,subheader,bodytext,tx_audio_audio,tx_audio_audiourl,tx_audio_poster,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,--palette--;;language,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,--palette--;;hidden,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,categories,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
            ],
    ];

    $GLOBALS['TCA']['tt_content']['types'] += $tempVideoTypes;
    $GLOBALS['TCA']['tt_content']['types'] += $tempAudioTypes;
})();
