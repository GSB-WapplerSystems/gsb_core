<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Ole Hartwig <o.hartwig@moselwal.de> 2023
 * (c) Matthias Peltzer <matthias.peltzer@digitaspixelpark.com> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

use ITZBund\GsbCore\Preview\StagePreviewRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['stage'] = 'tx_stage';
    $tempStageColumns = [
        'tx_stage_switch' =>
            [
                'exclude' => 0,
                'onChange' => 'reload',
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_switch_image',
                                        'value' => '0',
                                    ],
                                1 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_switch_video',
                                        'value' => '1',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_switch',
            ],
        'tx_stage_video' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'allowed' => 'mp4,webm,ogg',
                        'maxitems' => 1,
                        'minitems' => 0,
                    ],
                'displayCond' => 'FIELD:tx_stage_switch:=:1',
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_video',
            ],
        'tx_stage_position' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position.I.0',
                                        'value' => 'stage-default',
                                    ],
                                1 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position.I.1',
                                        'value' => 'stage-top',
                                    ],
                                2 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position.I.2',
                                        'value' => 'stage-middle',
                                    ],
                                3 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position.I.3',
                                        'value' => 'stage-bottom',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'exclude' => '0',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_position',
            ],
        'tx_stage_bgcolor' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_bgcolor.I.1',
                                        'labelChecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
                                        'labelUnchecked' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.disabled',
                                    ],
                            ],
                        'renderType' => 'checkboxToggle',
                        'type' => 'check',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:stage.tx_stage_bgcolor',
            ],
    ];

    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempStageColumns);

    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
        'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.CType.stage',
        'stage',
        'tx_stage',
        'default',
    ];

    $stagePositionPalettes = [
        'stageposition_config' => [
            'showitem' => 'tx_stage_position,tx_stage_bgcolor', 'canNotCollapse' => 1,
        ],
        'stagefile_config' => [
            'showitem' => 'tx_stage_switch,--linebreak--, tx_stage_video,--linebreak--,image,--linebreak--,video,--linebreak--,bodytext', 'canNotCollapse' => 1,
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
                  --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, date;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:date_formlabel, header_kicker,header,
                  --palette--;;header_config,subheader,
              --div--;LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.CType.stage,
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
