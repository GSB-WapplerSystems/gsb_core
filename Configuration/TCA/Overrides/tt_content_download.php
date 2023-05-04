<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['audio'] = 'tx_download';

    $tempDownload = [
        'tx_download' =>
            [
                'config' =>
                    [
                        'type' => 'file',
                        'allowed' => 'pdf',
                        'maxitems' => '1',
                        'minitems' => '0',
                    ],
                'exclude' => '1',
                'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_download',
            ],
    ];
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempDownload);

    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
        'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.CType.download',
        'audio',
        'tx_audio',
        'default',
    ];

    $tempAudioTypes = [
        'audio' =>
            [
                'columnsOverrides' =>
                    [
                        'bodytext' =>
                            [
                                'config' =>
                                    [
                                        'richtextConfiguration' => 'default',
                                        'enableRichtext' => 1,
                                    ],
                            ],
                    ],
                'showitem' => '
                  --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                      --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
                      --palette--;;header_config,subheader,bodytext,
                  --div--;Audio,
                      --palette--;tx_audio_audio,tx_audio_audiourl,tx_audio_poster,
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
                  --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended
              ',
            ],
    ];

    $GLOBALS['TCA']['tt_content']['types'] += $tempAudioTypes;
})();
