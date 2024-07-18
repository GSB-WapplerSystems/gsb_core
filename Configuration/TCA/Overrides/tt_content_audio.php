<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use ITZBund\GsbCore\Preview\AudioPreviewRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['audio'] = 'tx_audio';

    $tempAudioColumns = [
        'tx_audio_audio' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'allowed' => 'mp3,wav',
                        'maxitems' => 1,
                        'minitems' => 0,
                        'overrideChildTca' => [
                            'columns' => [
                                'description' => [
                                    'config' => [
                                        'type' => 'passthrough',
                                    ],
                                ],
                                'autoplay' => [
                                    'config' => [
                                        'renderType' => 'passthrough',
                                        'type' => 'passthrough',
                                    ],
                                ],
                            ],
                        ],
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_audio_audio',
            ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempAudioColumns);

    $audioPalettes = [
        'audio_config' => [
            'showitem' => 'tx_audio_audio,--linebreak--,image', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $audioPalettes;

    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
        'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.CType.audio',
        'audio',
        'tx_audio',
        'default',
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
                        'image' =>
                            [
                                'config' =>
                                    [
                                        'maxitems' => 1,
                                        'allowed' => 'jpg,jpeg,svg,png,gif',
                                        'overrideChildTca' => [
                                            'columns' => [
                                                'description' => [
                                                    'config' => [
                                                        'type' => 'passthrough',
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
                                                'outline' => [
                                                    'config' => [
                                                        'renderType' => 'passthrough',
                                                        'type' => 'passthrough',
                                                    ],
                                                ],
                                                'allow_download' => [
                                                    'config' => [
                                                        'renderType' => 'passthrough',
                                                        'type' => 'passthrough',
                                                    ],
                                                ],
                                                'caption' => [
                                                    'config' => [
                                                        'type' => 'passthrough',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                            ],
                    ],
                'showitem' => '
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header,
                        --palette--;;header_config,subheader,bodytext,
                    --div--;Audio,
                        --palette--;;audio_config,
                    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
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

    $GLOBALS['TCA']['tt_content']['types'] += $tempAudioTypes;

    $GLOBALS['TCA']['tt_content']['types']['audio']['previewRenderer'] = AudioPreviewRenderer::class;
})();
