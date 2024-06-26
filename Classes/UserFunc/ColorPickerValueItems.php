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
        $items = [
            [
                $this->getLanguageService()->sL('LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:page.configuration.color_0.label'),
                '',
            ],
        ];
        if (!method_exists($site, 'getConfiguration')) {
            $config['items'] = [];
            return;
        }

        $configuration = $site->getConfiguration();

        $colors = array_filter(
            $configuration,
            #function ($item) { return str_starts_with($item, 'color_'); },
            function ($item, $key) { return (int)preg_match('/^color_[0-9]+$/', $key) > 0 && !empty($item); },
            ARRAY_FILTER_USE_BOTH
        );

        foreach ($colors as $key => $color) {
            $label = $color;
            if (isset($configuration['label_color_' . substr($key, -1)]) && $configuration['label_color_' . substr($key, -1)] !== '') {
                $label = $configuration['label_color_' . substr($key, -1)];
            }

            $items[] = [$label, $key];
        }

        $config['items'] = $items;
    }

    public function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
