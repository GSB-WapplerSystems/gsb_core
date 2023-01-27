<?php

declare(strict_types=1);

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die();

(static function (): void {
    /**
     * Register tabs
     */
    GeneralUtility::makeInstance(Registry::class)->configureContainer(
        (
            new ContainerConfiguration(
                'ce_tabs',
                'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tabs.title',
                'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tabs.description',
                [
                    [
                        [
                            'name' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:content',
                            'colPos' => 101,
                        ],
                    ],
                ]
            )
        )
        ->setIcon('gsb-container-tabs')
        ->setBackendTemplate('EXT:gsb_template/Resources/Private/Templates/Backend/Container.html')
        ->setSaveAndCloseInNewContentElementWizard(true)
    );

    // override default settings
    $GLOBALS['TCA']['tt_content']['types']['ce_tabs']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header,
        --palette--;;header_config,
    --div--;LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tabs.title,container_tab_open,grid_container,
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
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended;
    ';

    $tabOpen = [
        'container_tab_open' => [
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tabs.label.onload',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tabs.description.onload',
            'config' => [
                'type' => 'number',
                'size' => 1,
                'range' => [
                    'lower' => 1,
                    'upper' => 20,
                ],
                'default' => 1,
                'slider' => [
                    'step' => 1,
                    'width' => 200,
                ],
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $tabOpen
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'container',
        'container_tab_open, --linebreak--'
    );
})();
