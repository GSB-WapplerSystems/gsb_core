<?php

declare(strict_types=1);

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die('Access denied.');

(static function (): void {
    /**
     * Register accordion
     */
    GeneralUtility::makeInstance(Registry::class)->configureContainer(
        (
        new ContainerConfiguration(
            'ce_accordion',
            'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:accordion.title',
            'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:accordion.description',
            [
                [
                    [
                        'name' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:content',
                        'colPos' => 101,
                        'allowed' => ['CType' => 'header, text, textpic'],
                    ],
                ],
            ]
        )
        )
            ->setIcon('tx_accordion')
            ->setBackendTemplate('EXT:gsb_core/Resources/Private/Backend/Templates/Container.html')
            ->setSaveAndCloseInNewContentElementWizard(true)
    );

    // override default settings
    $GLOBALS['TCA']['tt_content']['types']['ce_accordion']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
        --palette--;;header_config,subheader,
    --div--;LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:accordion.title,container_headline,grid_container,
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

    $accordionOpen = [
        'container_headline' => [
            'config' => [
                'items' =>
                    [
                        0 =>
                            [
                                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_style.default',
                                'value' => '1',
                            ],
                        1 =>
                            [
                                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:header_layout.2',
                                'value' => '2',
                            ],
                        2 =>
                            [
                                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:header_layout.3',
                                'value' => '3',
                            ],
                        3 =>
                            [
                                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:header_layout.4',
                                'value' => '4',
                            ],
                        4 =>
                            [
                                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:header_layout.5',
                                'value' => '5',
                            ],
                        5 =>
                            [
                                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:header_layout.6',
                                'value' => '6',
                            ],
                    ],
                'renderType' => 'selectSingle',
                'type' => 'select',
            ],
            'exclude' => 1,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_style',
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $accordionOpen
    );

    ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'container',
        '--linebreak--'
    );
})();
