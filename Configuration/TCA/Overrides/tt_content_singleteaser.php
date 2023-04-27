<?php

declare(strict_types=1);

use ITZBund\GsbTemplate\Preview\SingleteaserPreviewRenderer;
use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['gsb_singleteaser'] = 'tx_singleteaser';

    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:gsb_singleteaser.title',
            'gsb_singleteaser',
            'tx_singleteaser',
            'default',
        ]
    );

    $imageColumn = [
        'image' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.image',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'allowed' => 'jpg,jpeg,svg,png,gif',
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
        ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $imageColumn);

    $imagePalettes = [
        'image_config' => [
            'showitem' => 'image,--linebreak--,imageorient,imagewidth,--linebreak--,bodytext', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $imagePalettes;

    $singleteaserTypes = [
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
            --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, header_kicker, header,
            --palette--;;header_config,subheader,
        --div--;LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:gsb_singleteaser.title,
            --palette--;;image_config,
            --palette--;;link_config,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;
        frames,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
            ],
    ];

    $GLOBALS['TCA']['tt_content']['types'] += $singleteaserTypes;

    $GLOBALS['TCA']['tt_content']['types']['gsb_singleteaser']['previewRenderer'] = SingleteaserPreviewRenderer::class;
})();
