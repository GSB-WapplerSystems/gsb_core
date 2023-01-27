<?php

declare(strict_types=1);

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die();

(static function (): void {
    /**
     * Register columns4
     */
    GeneralUtility::makeInstance(Registry::class)->configureContainer(
        (
            new ContainerConfiguration(
                'ce_columns4',
                'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.fourColumnTitle',
                'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.fourColumnDescription',
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
                        [
                            'name' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column3',
                            'colPos' => 103,
                        ],
                        [
                            'name' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:columns.column4',
                            'colPos' => 104,
                        ],
                    ],
                ]
            )
        )
        ->setIcon('gsb-container-columns4')
        ->setBackendTemplate('EXT:gsb_template/Resources/Private/Templates/Backend/Container.html')
        ->setSaveAndCloseInNewContentElementWizard(true)
    );

    // override default settings
    $GLOBALS['TCA']['tt_content']['types']['ce_columns4']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
        --palette--;;header_config,
    --div--;LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:grid.title,columns_grid_col_1,columns_grid_col_2,columns_grid_col_3,columns_grid_col_4,grid_container,grid_bgcolor,grid_bgimage,grid_bgfullsize,grid_parallax,grid_bottom_image,grid_light,
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
})();
