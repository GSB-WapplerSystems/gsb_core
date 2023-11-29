<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Patrick Schriner <patrick.schriner@diemedialen.de> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
