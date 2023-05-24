<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $linkColumns = [
        'tx_link' =>
            [
                'config' =>
                    [
                        'allowedTypes' => [
                            '0' => 'page',
                            '1' => 'file',
                            '2' => 'url',
                            '3' => 'record',
                        ],
                        'appearance' => [
                            'browserTitle' => 'Link',
                        ],
                        'type' => 'link',
                        'wizards' => [
                            'link' => [
                                'icon' => 'actions-wizard-link',
                            ],
                        ],
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link',
            ],
        'tx_link_layout' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_style.default',
                                        1 => '',
                                    ],
                                1 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.0',
                                        1 => 'btn btn-primary',
                                    ],
                                2 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.1',
                                        1 => 'btn btn-secondary',
                                    ],
                                3 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.2',
                                        1 => 'btn btn-tertiary',
                                    ],
                                4 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.3',
                                        1 => 'btn btn-quarterly',
                                    ],
                                5 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.4',
                                        1 => 'internal-link',
                                    ],
                                6 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.5',
                                        1 => 'external-link',
                                    ],
                                7 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout.I.6',
                                        1 => 'download',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_layout',
            ],
        'tx_link_text' =>
            [
                'config' =>
                    [
                        'eval' => 'trim',
                        'type' => 'input',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_text',
            ],
        'tx_link_position' =>
            [
                'config' =>
                    [
                        'items' =>
                            [
                                0 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.header_style.default',
                                        1 => '',
                                    ],
                                1 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_position.I.0',
                                        1 => 'btn-center',
                                    ],
                                2 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_position.I.1',
                                        1 => 'btn-left',
                                    ],
                                3 =>
                                    [
                                        0 => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_position.I.2',
                                        1 => 'btn-right',
                                    ],
                            ],
                        'renderType' => 'selectSingle',
                        'type' => 'select',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_link_position',
            ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $linkColumns);

    $linkPalettes = [
        'link_config' => [
            'showitem' => 'tx_link_text,tx_link,tx_link_layout,tx_link_position', 'canNotCollapse' => 1,
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $linkPalettes;
})();
