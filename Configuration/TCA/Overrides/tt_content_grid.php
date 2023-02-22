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
                'LLL:EXT:gsb_template/Resources/Private/Language/Tca.xlf:grid.title',
                'LLL:EXT:gsb_template/Resources/Private/Language/Tca.xlf:grid.description',
                [
                    [
                        [
                            'name' => 'LLL:EXT:gsb_template/Resources/Private/Language/Tca.xlf:grid.elements',
                            'colPos' => 101,
                        ],
                    ],
                ]
            )
        )
        ->setIcon('tx_grid')
        ->setBackendTemplate('EXT:gsb_template/Resources/Private/Backend/Templates/Container.html')
        ->setSaveAndCloseInNewContentElementWizard(true)
    );

    // override default settings
    $GLOBALS['TCA']['tt_content']['types']['ce_grid']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
            --palette--;;header_config,subheader,
        --div--;LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.title,grid_type,grid_columns,grid_container,grid_bgcolor,grid_imgbg,grid_bgimage,grid_light,grid_bgfullsize,grid_parallax,grid_bottom_image,slider,slider_type,slider_columns,
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

    $grid = [
        'grid_type' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.type.list',
                                    1 => 'ul',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.type.container',
                                    1 => 'div',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.type',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.type.description',
        ],
        'grid_columns' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns.onecol',
                                    1 => '1',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns.twocol',
                                    1 => '2',
                                ],
                            2 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns.threecol',
                                    1 => '3',
                                ],
                            3 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns.fourcol',
                                    1 => '4',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'displayCond' => 'FIELD:slider:=:0',
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns.description',
        ],
        'slider' => [
            'exclude' => '0',
            'onChange' => 'reload',
            'label' => 'Slider',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.slider.description',
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
        'slider_type' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'Slide',
                                    1 => 'slide',
                                ],
                            1 =>
                                [
                                    0 => 'Fade',
                                    1 => 'fade',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'displayCond' => 'FIELD:slider:=:1',
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.slider.type',
        ],
        'slider_columns' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns.onecol',
                                    1 => '12',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns.twocol',
                                    1 => '6',
                                ],
                            2 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns.threecol',
                                    1 => '4',
                                ],
                            3 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.columns.fourcol',
                                    1 => '3',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'displayCond' => 'FIELD:slider:=:1',
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.slider.columns',
        ],
        'grid_bgimage' => [
                'config' =>
                    [
                        'type' => 'file',
                        'foreign_table' => 'sys_file_reference',
                        'foreign_field' => 'uid_foreign',
                        'foreign_sortby' => 'sorting_foreign',
                        'foreign_table_field' => 'tablenames',
                        'foreign_match_fields' =>
                            [
                                'fieldname' => 'grid_bgimage',
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
                                                                'elementBrowserAllowed' => 'jpg,jpeg,svg,png,gif',
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
                                'useSortable' => 'grid_bgimage',
                                'headerThumbnail' =>
                                    [
                                        'field' => 'uid_local',
                                        'width' => '45',
                                        'height' => '45c',
                                    ],
                                'enabledControls' =>
                                    [
                                        'info' => 'grid_bgimage',
                                        'new' => false,
                                        'dragdrop' => 'grid_bgimage',
                                        'sort' => false,
                                        'hide' => 'grid_bgimage',
                                        'delete' => 'grid_bgimage',
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
                'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bgimage',
            ],
        'grid_parallax' => [
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.parallax',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.parallax.description',
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
        'grid_imgbg' => [
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.imgbg',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.imgbg.description',
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
        'grid_bgcolor' => [
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.bgcolor',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.bgcolor.description',
            'config' => [
                'type' => 'input',
                'renderType' => 'color',
                'size' => 10,
            ],
        ],
        'grid_bgfullsize' => [
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.bgfullsize',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.bgfullsize.description',
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
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.container',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.container.description',
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
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.light',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.light.description',
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
        'grid_bottom_image' => [
            'exclude' => '0',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.wave',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.bg.wave.description',
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:silhouette.0',
                                    1 => '0',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:silhouette.1',
                                    1 => '1',
                                ],
                            2 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:silhouette.2',
                                    1 => '2',
                                ],
                            3 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:silhouette.3',
                                    1 => '3',
                                ],
                            4 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:silhouette.4',
                                    1 => '4',
                                ],
                            5 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:silhouette.5',
                                    1 => '5',
                                ],
                            6 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:silhouette.6',
                                    1 => '6',
                                ],
                            7 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:silhouette.7',
                                    1 => '7',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                    'default' => '0',
                ],
        ],
    ];

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
