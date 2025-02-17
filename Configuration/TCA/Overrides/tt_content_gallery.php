<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use ITZBund\GsbCore\Preview\GalleryPreviewRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['gallery'] = 'tx_gallery';

    $tempGalleryColumns = [
        'gallery_file' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'allowed' => 'png,jpg,jpeg,gif,svg',
                        'maxitems' => '100',
                        'minitems' => '0',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.file',
            ],
        'gallery_layout' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_layout.I.0',
                                        'value' => 'gallery-single',
                                    ],
                                1 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_layout.I.1',
                                        'value' => 'gallery-tiles',
                                    ],
                                2 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_layout.I.2',
                                        'value' => 'gallery-slider',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'exclude' => '0',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_layout',
            ],
        'gallery_bg' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                [
                                    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_bg.I.1',
                                    'labelChecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
                                    'labelUnchecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.disabled',
                                ],
                            ],
                        'renderType' => 'checkboxToggle',
                        'type' => 'check',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_bg',
            ],
        'gallery_textcolor' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                [
                                    'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_textcolor.I.1',
                                    'labelChecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
                                    'labelUnchecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.disabled',
                                ],
                            ],
                        'renderType' => 'checkboxToggle',
                        'type' => 'check',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_textcolor',
            ],
    ];

    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempGalleryColumns);

    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
        'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.CType.div._gallery_',
        '--div--',
    ];

    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
        'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.CType.gallery',
        'gallery',
        'tx_gallery',
        'default',
    ];

    $galleryPositionPalettes = [
        'galleryposition_config' => [
            'showitem' => 'gallery_layout,gallery_textcolor,gallery_bg', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $galleryPositionPalettes;

    $galleryTypes = [
        'gallery' =>
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
                      --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header,
                      --palette--;;header_config,subheader,bodytext,
                  --div--;LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.CType.gallery,
                      --palette--;;galleryposition_config,gallery_file,
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
                  --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended
              ',
            ],
    ];

    $GLOBALS['TCA']['tt_content']['types'] += $galleryTypes;

    $GLOBALS['TCA']['tt_content']['types']['gallery']['previewRenderer'] = GalleryPreviewRenderer::class;
})();
