<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use ITZBund\GsbTemplate\Preview\GalleryPreviewRenderer;

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['gallery'] = 'tx_gallery';

$tempGalleryColumns = [
    'gallery_file' =>
        [
            'config' =>
                [
                    'type' => 'inline',
                    'foreign_table' => 'sys_file_reference',
                    'foreign_field' => 'uid_foreign',
                    'foreign_sortby' => 'sorting_foreign',
                    'foreign_table_field' => 'tablenames',
                    'foreign_match_fields' =>
                        [
                            'fieldname' => 'gallery_file',
                        ],
                    'foreign_label' => 'uid_local',
                    'foreign_selector' => 'uid_local',
                    'overrideChildTca' =>
                        [
                            'columns' =>
                                [
                                    'uid_local' =>
                                        [
                                            'config' =>
                                                [
                                                    'appearance' =>
                                                        [
                                                            'elementBrowserType' => 'file',
                                                            'elementBrowserAllowed' => 'png,jpg,jpeg,gif,svg',
                                                        ],
                                                ],
                                        ],
                                ],
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
                    'filter' =>
                        [
                            0 =>
                                [
                                    'userFunc' => 'TYPO3\\CMS\\Core\\Resource\\Filter\\FileExtensionFilter->filterInlineChildren',
                                    'parameters' =>
                                        [
                                            'allowedFileExtensions' => 'png,jpg,jpeg,gif,svg',
                                        ],
                                ],
                        ],
                    'appearance' =>
                        [
                            'useSortable' => 'gallery_file',
                            'headerThumbnail' =>
                                [
                                    'field' => 'uid_local',
                                    'width' => '76c',
                                    'height' => '35c',
                                ],
                            'enabledControls' =>
                                [
                                    'info' => 'tx_gallery_file',
                                    'new' => false,
                                    'dragdrop' => 'tx_gallery_file',
                                    'sort' => false,
                                    'hide' => 'tx_gallery_file',
                                    'delete' => 'tx_gallery_file',
                                ],
                            'showAllLocalizationLink' => '1',
                            'showPossibleLocalizationRecords' => '1',
                            'showSynchronizationLink' => '1',
                            'fileUploadAllowed' => false,
                        ],
                    'maxitems' => '100',
                    'minitems' => '0',
                ],
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.file',
        ],
    'gallery_layout' =>
        [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_layout.I.0',
                                    1 => 'gallery-single',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_layout.I.1',
                                    1 => 'gallery-tiles',
                                ],
                            2 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_layout.I.2',
                                    1 => 'gallery-slider',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_layout',
        ],
    'gallery_bg' =>
        [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_bg.I.1',
                                ],
                        ],
                    'renderType' => 'checkboxToggle',
                    'type' => 'check',
                ],
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_bg',
        ],
    'gallery_textcolor' =>
        [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_textcolor.I.1',
                                ],
                        ],
                    'renderType' => 'checkboxToggle',
                    'type' => 'check',
                ],
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.gallery_textcolor',
        ],
];

ExtensionManagementUtility::addTCAcolumns('tt_content', $tempGalleryColumns);

$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
    'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.CType.div._gallery_',
    '--div--',
];

$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
    'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.CType.gallery',
    'gallery',
    'tx_gallery',
];

$galleryPositionPalettes = [
    'galleryposition_config' => [
        'showitem' => 'gallery_layout,gallery_textcolor,gallery_bg', 'canNotCollapse' => 1
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
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
                    --palette--;;header_config,subheader,bodytext,
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
