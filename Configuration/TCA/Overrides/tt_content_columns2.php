<?php

declare(strict_types=1);

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die();

(static function (): void {
    /**
     * Register columns2
     */
    GeneralUtility::makeInstance(Registry::class)->configureContainer(
        (
            new ContainerConfiguration(
                'ce_columns2',
                'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.twoColumnTitle',
                'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.twoColumnDescription',
                [
                    [
                        [
                            'name' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column1',
                            'colPos' => 101,
                        ],
                        [
                            'name' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column2',
                            'colPos' => 102,
                        ],
                    ],
                ]
            )
        )
        ->setIcon('gsb-container-columns2')
        ->setBackendTemplate('EXT:gsb_template/Resources/Private/Templates/Backend/Container.html')
        ->setSaveAndCloseInNewContentElementWizard(true)
    );

    // override default settings
    $GLOBALS['TCA']['tt_content']['types']['ce_columns2']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
        --palette--;;header_config,
    --div--;LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.title,columns_grid_col_1,columns_grid_col_2,grid_container,grid_bgcolor,grid_bgimage,grid_bgfullsize,grid_parallax,grid_bottom_image,grid_light,
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
