<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
  $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['video'] = 'tx_video';

  $tempVideoColumns = [
      'tx_video_caption' =>
          [
              'config' =>
                  [
                      'fieldControl' =>
                          [
                              'linkPopup' =>
                                  [
                                      'options' =>
                                          [
                                              'blindLinkOptions' => 'mail,page,folder,url',
                                              'title' => 'Link',
                                              'windowOpenParameters' => 'height=500,width=800,status=0,menubar=0,scrollbars=1',
                                          ],
                                  ],
                          ],
                      'renderType' => 'link',
                      'type' => 'input',
                      'wizards' =>
                          [
                              'link' =>
                                  [
                                      'icon' => 'actions-wizard-link',
                                      'params' =>
                                          [
                                              'allowedExtensions' => 'vtt,rst',
                                          ],
                                  ],
                          ],
                  ],
              'exclude' => '1',
              'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_caption',
          ],
      'tx_video_poster_image' =>
          [
              'config' =>
                  [
                      'type' => 'file',
                      'foreign_table' => 'sys_file_reference',
                      'foreign_field' => 'uid_foreign',
                      'foreign_sortby' => 'sorting_foreign',
                      'foreign_table_field' => 'tablenames',
                      'foreign_match_fields' =>
                          [
                              'fieldname' => 'tx_video_poster_image',
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
                              'useSortable' => 'tx_video_poster_image',
                              'headerThumbnail' =>
                                  [
                                      'field' => 'uid_local',
                                      'width' => '45',
                                      'height' => '45c',
                                  ],
                              'enabledControls' =>
                                  [
                                      'info' => 'tx_video_poster_image',
                                      'new' => false,
                                      'dragdrop' => 'tx_video_poster_image',
                                      'sort' => false,
                                      'hide' => 'tx_video_poster_image',
                                      'delete' => 'tx_video_poster_image',
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
              'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_poster_image',
          ],
      'tx_video_poster_video' =>
          [
              'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_poster_video_label',
              'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_poster_video_description',
              'config' => [
                  'type' => 'input',
                  'renderType' => 'link',
              ],
          ],
      'tx_video_video' =>
          [
              'config' =>
                  [
                      'fieldControl' =>
                          [
                              'linkPopup' =>
                                  [
                                      'options' =>
                                          [
                                              'blindLinkOptions' => 'mail,page,folder,url',
                                              'title' => 'Link',
                                              'windowOpenParameters' => 'height=500,width=800,status=0,menubar=0,scrollbars=1',
                                          ],
                                  ],
                          ],
                      'renderType' => 'link',
                      'type' => 'input',
                      'wizards' =>
                          [
                              'link' =>
                                  [
                                      'icon' => 'actions-wizard-link',
                                      'params' =>
                                          [
                                              'allowedExtensions' => 'mp4,webm,ogg',
                                          ],
                                  ],
                          ],
                  ],
              'exclude' => '1',
              'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_video',
          ],
      'tx_video_videourl' =>
          [
              'config' =>
                  [
                      'fieldControl' =>
                          [
                              'linkPopup' =>
                                  [
                                      'options' =>
                                          [
                                              'blindLinkOptions' => 'file,mail,page,folder',
                                              'title' => 'Link',
                                              'windowOpenParameters' => 'height=500,width=800,status=0,menubar=0,scrollbars=1',
                                          ],
                                  ],
                          ],
                      'renderType' => 'link',
                      'type' => 'input',
                      'wizards' =>
                          [
                              'link' =>
                                  [
                                      'icon' => 'actions-wizard-link',
                                  ],
                          ],
                  ],
              'exclude' => '1',
              'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_videourl',
          ],
      'tx_video_mainstage' => [
          'exclude' => 1,
          'label' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_mainstage_label',
          'description' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_mainstage_description',
          'config' => [
              'type' => 'check',
              'items' => [
                  ['LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_mainstage_checkbox', ''],
              ],
          ],
      ],
  ];
  ExtensionManagementUtility::addTCAcolumns('tt_content', $tempVideoColumns);

  $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
      'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:tt_content.CType.video',
      'video',
      'tx_video',
  ];

  $tempVideoTypes = [
      'video' =>
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
              'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,--palette--;;header_config,subheader,bodytext,tx_video_mainstage,tx_video_video,tx_video_videourl,tx_video_poster_image,tx_video_poster_video,tx_video_caption,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,--palette--;;language,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,--palette--;;hidden,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,categories,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
          ],
  ];

  $GLOBALS['TCA']['tt_content']['types'] += $tempVideoTypes;
})();
