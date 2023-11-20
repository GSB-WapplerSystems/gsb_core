<?php

$GLOBALS['TCA']['tt_content']['columns']['tx_video_caption']['config']['fieldInformation'] = [
    'elementInformationText' => [
        'renderType' => 'elementInformationText',
        'options' => [
            'texts' => [
                [
                    'text' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_caption',
                    'bold' => true,
                ],
                [
                    'text' => 'Simple text, just for test',
                    'italic' => true,
                ],
                [
                    'text' => 'Link text',
                    'link' => 'https://www.google.com',
                    'linkTarget' => '_self',
                ],
            ],
        ],
    ],
];

$GLOBALS['TCA']['tt_content']['types']['video']['infoButton'] = [
    'title' => 'This is an info button',
    'link' => 'https://google.com',
    'linkTarget' => '_blank',
];
