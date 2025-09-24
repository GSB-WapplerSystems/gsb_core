<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Willi Wehmeier
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\UserFunc;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;

class ColorPickerValueItems
{
    /**
     * @param array<string,mixed> $config
     */
    public function getItems(array &$config): void
    {
        /** @var SiteInterface $site */
        $site = $config['site'];
        $colors = [];

        $items = [
            [
                $this->getLanguageService()->sL('LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_0.label'),
                '',
            ],
        ];

        if (!method_exists($site, 'getSettings')) {
            $config['items'] = $items;
            return;
        }

        $settings = $site->getSettings();
        if (!isset($settings->get('colors')['background'])) {
            $config['items'] = $items;
            return;
        }

        $colors = $settings->get('colors')['background'];
        $config['items'] = array_merge(
            $items,
            [
                [$colors['gsb-background-color-1']['label'] ?? '', 'color_1'],
                [$colors['gsb-background-color-2']['label'] ?? '', 'color_2'],
                [$colors['gsb-background-color-3']['label'] ?? '', 'color_3'],
                [$colors['gsb-background-color-4']['label'] ?? '', 'color_4'],
                [$colors['gsb-background-color-5']['label'] ?? '', 'color_5'],
                [$colors['gsb-background-color-6']['label'] ?? '', 'color_6']
            ]
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getLanguageService(): LanguageService
    {
        if (!isset($GLOBALS['LANG']) || !$GLOBALS['LANG'] instanceof LanguageService) {
            throw new \RuntimeException('Language service not available');
        }
        return $GLOBALS['LANG'];
    }
}
