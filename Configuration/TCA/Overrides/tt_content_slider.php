<?php

declare(strict_types=1);

use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die();

(static function (): void {
    /**
     * Register columns2
     */
    GeneralUtility::makeInstance(Registry::class)->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                'ce_slider', // CType
                'Slider', // label
                'Container element for Slider', // description
                [
                    [
                        ['name' => 'header', 'colPos' => 200, 'colspan' => 2, 'allowed' => ['CType' => 'header, textpic']],
                    ],
                ] // grid configuration
            )
        )
        ->setIcon('gsb-container-grid')
        ->setBackendTemplate('EXT:gsb_template/Resources/Private/Templates/Backend/Container.html')
        ->setSaveAndCloseInNewContentElementWizard(true)
    );

    // override default settings
    $GLOBALS['TCA']['tt_content']['types']['ce_slider']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
        --palette--;;header_config,
    --div--;LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.title,columns_grid_col_1,columns_grid_col_2,grid_container,grid_bgcolor,grid_bgimage,grid_type,slider_type,slider_columns,
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

    $columns = [
        'columns_grid_col_1' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.0',
                                    1 => 'col-12 col-lg-1',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.1',
                                    1 => 'col-12 col-lg-2',
                                ],
                            2 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.2',
                                    1 => 'col-12 col-lg-3',
                                ],
                            3 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.3',
                                    1 => 'col-12 col-lg-4',
                                ],
                            4 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.4',
                                    1 => 'col-12 col-lg-5',
                                ],
                            5 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.5',
                                    1 => 'col-12 col-lg-6',
                                ],
                            6 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.6',
                                    1 => 'col-12 col-lg-7',
                                ],
                            7 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.7',
                                    1 => 'col-12 col-lg-8',
                                ],
                            8 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.8',
                                    1 => 'col-12 col-lg-9',
                                ],
                            9 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.9',
                                    1 => 'col-12 col-lg-10',
                                ],
                            10 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.10',
                                    1 => 'col-12 col-lg-11',
                                ],
                            11 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.11',
                                    1 => 'col-12',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.first',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.first.description',
        ],
        'columns_grid_col_2' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.0',
                                    1 => 'col-12 col-lg-1',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.1',
                                    1 => 'col-12 col-lg-2',
                                ],
                            2 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.2',
                                    1 => 'col-12 col-lg-3',
                                ],
                            3 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.3',
                                    1 => 'col-12 col-lg-4',
                                ],
                            4 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.4',
                                    1 => 'col-12 col-lg-5',
                                ],
                            5 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.5',
                                    1 => 'col-12 col-lg-6',
                                ],
                            6 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.6',
                                    1 => 'col-12 col-lg-7',
                                ],
                            7 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.7',
                                    1 => 'col-12 col-lg-8',
                                ],
                            8 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.8',
                                    1 => 'col-12 col-lg-9',
                                ],
                            9 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.9',
                                    1 => 'col-12 col-lg-10',
                                ],
                            10 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.10',
                                    1 => 'col-12 col-lg-11',
                                ],
                            11 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.11',
                                    1 => 'col-12',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.second',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.second.description',
        ],
        'columns_grid_col_3' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.0',
                                    1 => 'col-12 col-lg-1',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.1',
                                    1 => 'col-12 col-lg-2',
                                ],
                            2 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.2',
                                    1 => 'col-12 col-lg-3',
                                ],
                            3 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.3',
                                    1 => 'col-12 col-lg-4',
                                ],
                            4 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.4',
                                    1 => 'col-12 col-lg-5',
                                ],
                            5 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.5',
                                    1 => 'col-12 col-lg-6',
                                ],
                            6 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.6',
                                    1 => 'col-12 col-lg-7',
                                ],
                            7 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.7',
                                    1 => 'col-12 col-lg-8',
                                ],
                            8 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.8',
                                    1 => 'col-12 col-lg-9',
                                ],
                            9 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.9',
                                    1 => 'col-12 col-lg-10',
                                ],
                            10 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.10',
                                    1 => 'col-12 col-lg-11',
                                ],
                            11 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.11',
                                    1 => 'col-12',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.third',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.third.description',
        ],
        'columns_grid_col_4' => [
            'config' =>
                [
                    'items' =>
                        [
                            0 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.0',
                                    1 => 'col-12 col-lg-1',
                                ],
                            1 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.1',
                                    1 => 'col-12 col-lg-2',
                                ],
                            2 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.2',
                                    1 => 'col-12 col-lg-3',
                                ],
                            3 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.3',
                                    1 => 'col-12 col-lg-4',
                                ],
                            4 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.4',
                                    1 => 'col-12 col-lg-5',
                                ],
                            5 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.5',
                                    1 => 'col-12 col-lg-6',
                                ],
                            6 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.6',
                                    1 => 'col-12 col-lg-7',
                                ],
                            7 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.7',
                                    1 => 'col-12 col-lg-8',
                                ],
                            8 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.8',
                                    1 => 'col-12 col-lg-9',
                                ],
                            9 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.9',
                                    1 => 'col-12 col-lg-10',
                                ],
                            10 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.10',
                                    1 => 'col-12 col-lg-11',
                                ],
                            11 =>
                                [
                                    0 => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.11',
                                    1 => 'col-12',
                                ],
                        ],
                    'renderType' => 'selectSingle',
                    'type' => 'select',
                ],
            'exclude' => '1',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.fourth',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column.fourth.description',
        ],
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
                'default' => '1',
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
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $columns
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'container',
        'columns_grid_col_1, columns_grid_col_2'
    );
})();
