<?php

declare(strict_types=1);

use ITZBund\GsbTemplate\Preview\StagePreviewRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['stage'] = 'tx_stage';

    $tempStageColumns = [
        'tx_stage_file' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'foreign_table' => 'sys_file_reference',
                        'foreign_field' => 'uid_foreign',
                        'foreign_sortby' => 'sorting_foreign',
                        'foreign_table_field' => 'tablenames',
                        'foreign_match_fields' =>
                            [
                                'fieldname' => 'tx_stage_file',
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
                                                                'elementBrowserAllowed' => 'png,jpg,jpeg,gif,svg,mp4',
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
                        'appearance' =>
                            [
                                'useSortable' => 'tx_stage_file',
                                'headerThumbnail' =>
                                    [
                                        'field' => 'uid_local',
                                        'width' => '76c',
                                        'height' => '35c',
                                    ],
                                'enabledControls' =>
                                    [
                                        'info' => 'tx_stage_file',
                                        'new' => false,
                                        'dragdrop' => 'tx_stage_file',
                                        'sort' => false,
                                        'hide' => 'tx_stage_file',
                                        'delete' => 'tx_stage_file',
                                    ],
                                'showAllLocalizationLink' => '1',
                                'showPossibleLocalizationRecords' => '1',
                                'showSynchronizationLink' => '1',
                                'fileUploadAllowed' => false,
                            ],
                        'maxitems' => '1',
                        'minitems' => '0',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_file',
            ],
        'tx_stage_position' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position.I.0',
                                        1 => 'stage-default',
                                    ],
                                1 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position.I.1',
                                        1 => 'stage-top',
                                    ],
                                2 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position.I.2',
                                        1 => 'stage-middle',
                                    ],
                                3 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position.I.3',
                                        1 => 'stage-bottom',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'exclude' => '0',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position',
            ],
        'tx_stage_bgcolor' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_bgcolor.I.1',
                                    ],
                            ],
                        'renderType' => 'checkboxToggle',
                        'type' => 'check',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_bgcolor',
            ],
        'tx_stage_bg' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_bg.I.1',
                                    ],
                            ],
                        'renderType' => 'checkboxToggle',
                        'type' => 'check',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_bg',
            ],
    ];

    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempStageColumns);

    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
        'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.CType.stage',
        'stage',
        'tx_stage',
        'default',
    ];

    $stagePositionPalettes = [
        'stageposition_config' => [
            'showitem' => 'tx_stage_position,tx_stage_bg,tx_stage_bgcolor', 'canNotCollapse' => 1,
        ],
        'stagefile_config' => [
            'showitem' => 'tx_stage_file,--linebreak--,bodytext', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $stagePositionPalettes;

    $stageTypes = [
        'stage' =>
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
                  --palette--;;header_config,subheader,
              --div--;LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.CType.stage,
                  --palette--;;stagefile_config,
                  --palette--;;stageposition_config,
                  --palette--;;link_config,
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

    $GLOBALS['TCA']['tt_content']['types'] += $stageTypes;

    $GLOBALS['TCA']['tt_content']['types']['stage']['previewRenderer'] = StagePreviewRenderer::class;
})();
