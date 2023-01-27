<?php

defined('TYPO3') || die();

return [
    'ctrl' =>
        [
            'title' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tx_gsbfile_file',
            'label' => null,
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'versioningWS' => true,
            'language' => 'sys_language_uid',
            'transOrigPointerField' => 'l10n_parent',
            'transOrigDiffSourceField' => 'l10n_diffsource',
            'delete' => 'deleted',
            'enablecolumns' =>
                [
                    'disabled' => 'hidden',
                    'starttime' => 'starttime',
                    'endtime' => 'endtime',
                ],
            'searchFields' => '',
            'dynamicConfigFile' => '',
            'iconfile' => 'EXT:gsb_template/Resources/Public/Icons/Extension.svg',
            'hideTable' => true,
            'security' => [
                'ignorePageTypeRestriction' => true,
            ]
        ],
    'types' =>
        [
            1 =>
                [
                    'showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime,endtime',
                ],
        ],
    'palettes' =>
        [
            1 =>
                [
                    'showitem' => '',
                ],
        ],
    'columns' =>
        [
            'sys_language_uid' =>
                [
                    'exclude' => 1,
                    'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
                    'config' =>
                        [
                            'type' => 'select',
                            'renderType' => 'selectSingle',
                            'items' =>
                                [
                                    0 =>
                                        [
                                            0 => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                                            1 => -1,
                                            2 => 'flags-multiple',
                                        ],
                                ],
                            'language' => 'languages',
                            'default' => 0,
                        ],
                ],
            'l10n_parent' =>
                [
                    'displayCond' => 'FIELD:sys_language_uid:>:0',
                    'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
                    'config' =>
                        [
                            'type' => 'select',
                            'renderType' => 'selectSingle',
                            'items' =>
                                [
                                    0 =>
                                        [
                                            0 => '',
                                            1 => 0,
                                        ],
                                ],
                            'foreign_table' => 'tx_gsbfile_file',
                            'foreign_table_where' => 'AND tx_gsbfile_file.pid=###CURRENT_PID### AND tx_gsbfile_file.sys_language_uid IN (-1,0)',
                            'default' => 0,
                        ],
                ],
            'l10n_diffsource' =>
                [
                    'config' =>
                        [
                            'type' => 'passthrough',
                        ],
                ],
            't3ver_label' =>
                [
                    'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
                    'config' =>
                        [
                            'type' => 'input',
                            'size' => 30,
                            'max' => 255,
                        ],
                ],
            'hidden' =>
                [
                    'exclude' => 1,
                    'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
                    'config' =>
                        [
                            'type' => 'check',
                        ],
                ],
            'starttime' =>
                [
                    'exclude' => 1,
                    'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
                    'config' =>
                        [
                            'behaviour' =>
                                [
                                    'allowLanguageSynchronization' => true,
                                ],
                            'type' => 'datetime',
                            'size' => 13,
                            'default' => 0,
                        ],
                ],
            'endtime' =>
                [
                    'exclude' => 1,
                    'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
                    'config' =>
                        [
                            'behaviour' =>
                                [
                                    'allowLanguageSynchronization' => true,
                                ],
                            'type' => 'datetime',
                            'size' => 13,
                            'default' => 0,
                        ],
                ],
            'parentid' =>
                [
                    'config' =>
                        [
                            'type' => 'select',
                            'renderType' => 'selectSingle',
                            'items' =>
                                [
                                    0 =>
                                        [
                                            0 => '',
                                            1 => 0,
                                        ],
                                ],
                            'foreign_table' => 'tt_content',
                            'foreign_table_where' => 'AND tt_content.pid=###CURRENT_PID### AND tt_content.sys_language_uid IN (-1,###REC_FIELD_sys_language_uid###)',
                        ],
                ],
            'parenttable' =>
                [
                    'config' =>
                        [
                            'type' => 'passthrough',
                        ],
                ],
            'sorting' =>
                [
                    'config' =>
                        [
                            'type' => 'passthrough',
                        ],
                ],
        ],
];
