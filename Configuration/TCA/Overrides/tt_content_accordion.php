<?php

declare(strict_types=1);

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die();

(static function (): void {
    /**
     * Register accordion
     */
    GeneralUtility::makeInstance(Registry::class)->configureContainer(
        (
        new ContainerConfiguration(
            'ce_accordion',
            'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:accordion.title',
            'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:accordion.description',
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
        ->setIcon('gsb-container-accordion')
        ->setBackendTemplate('EXT:gsb_template/Resources/Private/Templates/Backend/Container.html')
        ->setSaveAndCloseInNewContentElementWizard(true)
    );

    // override default settings
    $GLOBALS['TCA']['tt_content']['types']['ce_accordion']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
        --palette--;;header_config,
    --div--;LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:accordion.title,container_headline,container_accordion_toggle_all,container_accordion_toggle,container_accordion_open,grid_container,
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

    $accordionOpen = [
        'container_headline' => [
            'config' => [
                'items' =>
                    [
                        0 =>
                            [
                                0 => 'LLL:EXT:civic_template/Resources/Private/Language/locallang_db.xlf:tt_content.header_style.default',
                                1 => '',
                            ],
                        1 =>
                            [
                                0 => 'H2',
                                1 => '2',
                            ],
                        2 =>
                            [
                                0 => 'H3',
                                1 => '3',
                            ],
                        3 =>
                            [
                                0 => 'H4',
                                1 => '4',
                            ],
                        4 =>
                            [
                                0 => 'H5',
                                1 => '5',
                            ],
                        5 =>
                            [
                                0 => 'H6',
                                1 => '6',
                            ],
                    ],
                'renderType' => 'selectSingle',
                'type' => 'select',
            ],
            'exclude' => 1,
            'label' => 'LLL:EXT:civic_template/Resources/Private/Language/locallang_db.xlf:tt_content.header_style',
        ],
        'container_accordion_toggle' => [
            'exclude' => 1,
            'onChange' => 'reload',
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:accordion.label.onload',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:accordion.description.onload',
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
        'container_accordion_open' => [
            'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:accordion.label.open',
            'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:accordion.description.open',
            'config' => [
                'type' => 'number',
                'size' => 1,
                'range' => [
                    'lower' => 1,
                    'upper' => 50,
                ],
                'default' => 1,
                'slider' => [
                    'step' => 1,
                    'width' => 200,
                ],
            ],
            'displayCond' => 'FIELD:container_accordion_toggle:=:1',
        ],
        'container_accordion_toggle_all' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:accordion.label.onloadall',
        'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:accordion.description.onloadall',
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

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $accordionOpen
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'container',
        'container_accordion_toggle, container_accordion_open, --linebreak--'
    );
})();
