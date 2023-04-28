<?php

declare(strict_types=1);

use ITZBund\GsbCore\Preview\VideoPreviewRenderer;
use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['video'] = 'tx_video';

    $tempVideoColumns = [
        'tx_video_caption' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'allowed' => 'vtt',
                        'maxitems' => 1,
                        'minitems' => 0,
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_caption',
            ],
        'tx_video_poster_image' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'allowed' => 'jpg,jpeg,svg,png,gif',
                        'maxitems' => 1,
                        'minitems' => 0,
                        'overrideChildTca' => [
                            'types' => [
                                '0' => [
                                    'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                                ],
                                AbstractFile::FILETYPE_IMAGE => [
                                    'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                                ],
                            ],
                        ],
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_poster_image',
            ],
        'tx_video_video' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'allowed' => 'mp4,webm,ogg,youtube,vimeo',
                        'maxitems' => 1,
                        'minitems' => 0,
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_video',
            ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempVideoColumns);

    $videoPalettes = [
        'video_config' => [
            'showitem' => 'tx_video_video,--linebreak--,tx_video_poster_image,--linebreak--,tx_video_caption', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $videoPalettes;

    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
        'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.CType.video',
        'video',
        'tx_video',
        'default',
    ];

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
                'showitem' => '
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
                        --palette--;;header_config,subheader,bodytext,
                    --div--;Video,
                        --palette--;;video_config,
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

    $GLOBALS['TCA']['tt_content']['types'] += $tempVideoTypes;

    $GLOBALS['TCA']['tt_content']['types']['video']['previewRenderer'] = VideoPreviewRenderer::class;
})();
