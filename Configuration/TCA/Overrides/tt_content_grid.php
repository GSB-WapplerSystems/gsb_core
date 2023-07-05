<?php

declare(strict_types=1);

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die('Access denied.');

(static function (): void {
    /**
     * Register grids
     */
    GeneralUtility::makeInstance(Registry::class)->configureContainer(
        (
            new ContainerConfiguration(
                'ce_grid',
                'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.title',
                'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.description',
                [
                    [
                        [
                            'name' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.elements',
                            'colPos' => 101,
                        ],
                    ],
                ]
            )
        )
        ->setIcon('tx_grid')
        ->setBackendTemplate('EXT:gsb_core/Resources/Private/Backend/Templates/Container.html')
        ->setSaveAndCloseInNewContentElementWizard(true)
    );

    $grid = [
        'columnsOverrides' =>
            [
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
                                        'allow_download' => [
                                            'config' => [
                                                'renderType' => 'passthrough',
                                                'type' => 'passthrough',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                    ],
            ],
        'grid_type' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.type.list',
                                    1 => 'ul',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.type.container',
                                    1 => 'div',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.type',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.type.description',
        ],
        'grid_columns' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.columns.onecol',
                                    1 => '1',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.columns.twocol',
                                    1 => '2',
                                ],
                            2 =>
                                [
                                    0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.columns.threecol',
                                    1 => '3',
                                ],
                            3 =>
                                [
                                    0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.columns.fourcol',
                                    1 => '4',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.columns',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.columns.description',
        ],
        'grid_bgcolor' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.bgcolor',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.bgcolor.description',
            'config' => [
                'type' => 'input',
                'renderType' => 'color',
                'size' => 10,
            ],
        ],
        'grid_imgbg' => [
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.imgbg',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.imgbg.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '0',
                        1 => '1',
                    ],
                ],
                'default' => '0',
            ],
        ],
        'grid_light' => [
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.light',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.light.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '0',
                        1 => '1',
                    ],
                ],
                'default' => '0',
            ],
        ],
        'grid_bgfullsize' => [
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.bgfullsize',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.bgfullsize.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '0',
                        1 => '1',
                    ],
                ],
                'default' => '0',
            ],
        ],
        'grid_container' => [
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.container',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.bg.container.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '0',
                        1 => '1',
                    ],
                ],
                'default' => '0',
            ],
        ],
    ];

    $gridPalettes = [
        'grid_config' => [
            'showitem' => 'grid_type,grid_columns', 'canNotCollapse' => 1,
        ],
        'grid_bg' => [
            'showitem' => 'grid_bgcolor,grid_light,--linebreak--,image', 'canNotCollapse' => 1,
        ],
        'grid_container_pallet' => [
            'showitem' => 'grid_bgfullsize,grid_container', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $gridPalettes;

    // override default settings
    $GLOBALS['TCA']['tt_content']['types']['ce_grid']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
            --palette--;;header_config,subheader,
        --div--;LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:grid.title,
            --palette--;;grid_config,grid_container,
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
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended;'
    ;

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $grid
    );

    ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'container',
        'grid'
    );
})();
