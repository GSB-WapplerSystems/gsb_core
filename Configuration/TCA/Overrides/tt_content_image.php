<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

defined('TYPO3') || die();

(static function (): void {
    // Erstelle die Bild-Link-Palette
    $palettes = [
        'image_link_config' => [
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.palettes.image_link_config.label',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.palettes.image_link_config.description',
            'showitem' => 'tx_link',
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $palettes;

    if (isset($GLOBALS['TCA']['tt_content']['types']['image']['showitem'])) {
        $showitem = $GLOBALS['TCA']['tt_content']['types']['image']['showitem'];

        if (str_contains($showitem, 'image,')) {
            $showitem = str_replace('image,', 'image,
                tx_link,', $showitem);
        } elseif (str_contains($showitem, 'image;')) {
            $showitem = str_replace('image;', 'image;
                tx_link,', $showitem);
        } else {
            $showitem = rtrim($showitem, ',') . ',
                tx_link,';
        }

        $GLOBALS['TCA']['tt_content']['types']['image']['showitem'] = $showitem;
    }
})();
