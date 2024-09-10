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

namespace ITZBund\GsbCore\Utility;

use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class ExtendSiteUtility
{
    public static function extendSiteWithLocalizationOverload(Site $site, SiteLanguage $language): Site
    {
        $localizedConfig = static::overloadWithLocalizedConfig($site->getConfiguration(), $language->getLanguageId());

        return new Site($site->getIdentifier(), $site->getRootPageId(), $localizedConfig, $site->getSettings());
    }

    /**
     * @param array<string,mixed> $config
     * @return array<string,mixed>
     */
    public static function overloadWithLocalizedConfig(array $config, int $languageId): array
    {
        $languages = $config['languages'] ?? [];

        $languageConfig = array_filter($languages, function (array $language) use ($languageId) {
            return (int)$language['languageId'] === $languageId;
        });

        if (count($languageConfig) !== 1) {
            return $config;
        }

        /** @var array<string,mixed> $languageConfig */
        $languageConfig = reset($languageConfig);

        foreach ($languageConfig as $key => $value) {
            if (!isset($config[$key])) {
                continue;
            }
            $config[$key] = $value;
        }

        return $config;
    }

    /**
     * @param array<string,mixed> $config
     * @return array<string,mixed>
     */
    public static function copyToggleFieldsToLanguageConfigs(array $config): array
    {
        $toggleFields = self::getLocalizationToggleFields($config);

        foreach ($config['languages'] as &$languageConfig) {
            foreach ($toggleFields as $toggleFieldKey) {
                $languageConfig[$toggleFieldKey] = $config[$toggleFieldKey];
            }
        }

        return $config;
    }

    /**
     * @param array<string,mixed> $config
     * @return string[]
     */
    public static function getLocalizationToggleFields(array $config): array
    {
        return array_values(array_filter(
            array_keys($config),
            function (string $key) {
                return str_contains($key, 'toggle');
            }
        ));
    }

    /**
     * @param array<string,mixed> $settings
     * @param array<string,mixed> $controlFields
     * @return array<string,mixed>
     */
    public static function removeSelectedNullableFields(array $settings, array $controlFields): array
    {
        foreach ($settings['languages'] as $key => &$language) {
            $languageControls = $controlFields['site_language'][$key] ?? [];
            foreach ($languageControls as $controlledField => $useOverrideFieldValue) {
                if ((int)$useOverrideFieldValue === 0 && array_key_exists($controlledField, $language)) {
                    unset($language[$controlledField]);
                }
            }
        }

        return $settings;
    }
}
