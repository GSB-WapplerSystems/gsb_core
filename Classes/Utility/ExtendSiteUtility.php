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
use TYPO3\CMS\Core\Site\Entity\SiteSettings;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class ExtendSiteUtility
{
    public const LOCALIZEDCONFIGURATION = [
        'initiative-text' => 'logos.gsb-initiative-text',
        'logo-complete-big' => 'logos.gsb-logo-big',
        'logo-complete-small' => 'logos.gsb-logo-small',
        'second-logo' => 'logos.gsb-second-logo',
        'second-logo-alt' => 'logos.gsb-second-logo-alt',
        'second-logo-link' => 'logos.gsb-second-logo-link',
    ];

    public function extendSiteWithLocalizationOverload(Site $site, SiteLanguage $language): Site
    {
        $siteConfiguration = $site->getConfiguration();
        $localizedConfig = $this->overloadWithLocalizedConfig($siteConfiguration, $language->getLanguageId());
        $settings = $site->getSettings()->getAllFlat();
        $localizedSettingsTree = $this->overrideSettingsWithLocalizedConfig($settings, $siteConfiguration, $language->getLanguageId());
        $localizedSettings = SiteSettings::createFromSettingsTree($localizedSettingsTree);
        return new Site($site->getIdentifier(), $site->getRootPageId(), $localizedConfig, $localizedSettings);
    }

    /**
     * @param array<string,mixed> $settings
     * @param array<string,mixed> $configuration
     * @param int $languageId
     * @return array<string,mixed>
     */
    protected function overrideSettingsWithLocalizedConfig(array $settings, array $configuration, int $languageId): array
    {
        $configuration = $this->getLocalizedPartOfConfiguration($configuration, $languageId);
        foreach (self::LOCALIZEDCONFIGURATION as $old => $new) {
            if (isset($configuration[$old])) {
                $settings[$new] = $configuration[$old];
            }
        }
        return ArrayUtility::unflatten($settings);
    }

    /**
     * @param array<string,mixed> $config
     * @return array<string,mixed>
     */
    protected function overloadWithLocalizedConfig(array $config, int $languageId): array
    {
        $languageConfig = $this->getLocalizedPartOfConfiguration($config, $languageId);

        foreach ($languageConfig as $key => $value) {
            if (!isset($config[$key])) {
                // why should one not overwrite this?
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
    public function copyToggleFieldsToLanguageConfigs(array $config): array
    {
        $toggleFields = $this->getLocalizationToggleFields($config);

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
    public function getLocalizationToggleFields(array $config): array
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
    public function removeSelectedNullableFields(array $settings, array $controlFields): array
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

    /**
     * @param array<string,mixed> $config
     * @param int $languageId
     * @return array<string,mixed>
     */
    protected function getLocalizedPartOfConfiguration(array $config, int $languageId): array
    {
        $languages = $config['languages'] ?? [];

        $languageConfig = array_filter($languages, function (array $language) use ($languageId) {
            return (int)$language['languageId'] === $languageId;
        });

        if (count($languageConfig) !== 1) {
            return [];
        }
        return reset($languageConfig);
    }
}
