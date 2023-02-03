<?php

declare(strict_types=1);

defined('TYPO3') || die();

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['gsbfile_gsb_download'] = 'tx_gsbfile_gsb_download';
    $tempColumns = [
        'tx_gsbfile_file' =>
            [
                'exclude' => '1',
                'config' =>
                    [
                        'type' => 'file',
                        'allowed' => 'docx,doc,xlsx,xls,ppt,pptx,txt,pdf,gif,jpg,png,svg',
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
                                    ],
                            ],
                        'appearance' =>
                            [
                                'useSortable' => 'tx_gsbfile_file',
                                'headerThumbnail' =>
                                    [
                                        'field' => 'uid_local',
                                        'width' => '45',
                                        'height' => '45c',
                                    ],
                                'enabledControls' =>
                                    [
                                        'info' => 'tx_gsbfile_file',
                                        'new' => false,
                                        'dragdrop' => 'tx_gsbfile_file',
                                        'sort' => false,
                                        'hide' => 'tx_gsbfile_file',
                                        'delete' => 'tx_gsbfile_file',
                                    ],
                                'fileUploadAllowed' => false,
                            ],
                    ],
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_gsbfile_file',
            ],
    ];

    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);

    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.CType.gsbfile_gsb_download',
            'gsbfile_gsb_download',
            'tx_gsbfile_gsb_download',
        ]
    );

    $headerFilePalettes = [
        'headerfile_config' => [
            'showitem' => 'header_layout,header_position,tx_header_style', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $headerFilePalettes;

    $tempTypes = [
        'gsbfile_gsb_download' =>
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
                'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header,--palette--;;headerfile_config,tx_gsbfile_file,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,--palette--;;language,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,--palette--;;hidden,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,categories,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
            ],
    ];

    $GLOBALS['TCA']['tt_content']['types'] += $tempTypes;

})();
