<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2025 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Christian Rath-Ulrich
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Upgrades;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;

/**
 * Combined upgrade wizard to move site configuration and TypoScript constants to site settings
 *
 * Can be manually started with
 *
 * `typo3 upgrade:run gsbcore_moveConfigurationToSettings`
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
#[UpgradeWizard('gsbcore_moveConfigurationToSettings')]
class MoveConfigurationToSettings extends AbstractMoveConfigurationToSettings
{
    /**
     * Site config keys that should be moved to site settings
     */
    protected const CONFIG_KEYS = [
        'navType',
        'copyright',
        'show-copyright',
        'google_site_verification',
        'solr_enabled_facets',
        'solrShowTopResults',
        'search',
        'sign-language-page',
        'simple-language-page',
        'color_1',
        'label_color_1',
        'color_2',
        'label_color_2',
        'color_3',
        'label_color_3',
        'color_4',
        'label_color_4',
        'color_5',
        'label_color_5',
        'color_6',
        'label_color_6',
        'color_primary',
        'color_secondary',
        'color_secondary_rgba',
        'color_tertiary',
        'logo-complete-toggle',
        'logo-complete-big',
        'logo-complete-small',
        'logo-text',
        'second-logo-complete-toggle',
        'second-logo',
        'second-logo-alt',
        'second-logo-link',
        'initiative-text-toggle',
        'initiative-text',
        'favicon-96x96-png',
        'faviconIco',
        'faviconSvg',
        'apple-touch-icon',
        'web-app-manifest-192x192',
        'web-app-manifest-512x512',
        'webmanifest',
        'apple-touch-icon-120x120',
        'apple-touch-icon-152x152',
        'apple-touch-icon-180x180',
        'apple-touch-icon-60x60',
        'apple-touch-icon-76x76',
        'browserconfig',
        'color_quaternary',
        'display-brand-topline',
        'favicon-16x16',
        'favicon-32x32',
        'font-switch',
        'safari-pinned-tab',
        'shortcut-icon',
        'sitePackage',
        'apple-touch-icon-152x152',
        'apple-touch-icon-180x180',
        'apple-touch-icon-60x60',
        'apple-touch-icon-76x76',
        'browserconfig',
        'color_quaternary',
        'favicon-16x16',
        'favicon-32x32',
        'safari-pinned-tab',
        'shortcut-icon',
        'sitePackage',
        'font-sans',
        'font-sans-bold',
        'font-sans-bold-italic',
        'font-sans-italic',
        'font-sans-medium',
        'font-sans-name',
        'font-serif',
        'font-serif-italic',
        'font-serif-name',
    ];

    /**
     * Returns the title of the upgrade wizard.
     */
    public function getTitle(): string
    {
        return 'Move configuration to site settings';
    }

    /**
     * Returns the description of the upgrade wizard.
     */
    public function getDescription(): string
    {
        return 'Moves site configuration and TypoScript constants to site settings. If settings.yaml does not exist, it will be created.';
    }

    /**
     * Returns the wizard identifier.
     */
    public function getWizardIdentifier(): string
    {
        return 'gsbcore_moveConfigurationToSettings';
    }

    /**
     * Returns the config keys that should be moved to site settings.
     */
    protected function getConfigKeys(): array
    {
        return self::CONFIG_KEYS;
    }

    /**
     * Checks if the update is necessary for the given site.
     */
    protected function isUpdateNecessaryForSite(string $siteIdentifier, int $siteId): bool
    {
        // Check if settings.yaml doesn't exist
        return !$this->checkIfSettingsFileExists($siteIdentifier);
    }

    /**
     * Checks if settings file exists for the given site.
     */
    protected function checkIfSettingsFileExists(string $siteIdentifier): bool
    {
        $configPath = Environment::getConfigPath() . '/sites/' . $siteIdentifier;
        $settingsFile = $configPath . '/settings.yaml';
        return file_exists($settingsFile);
    }

    /**
     * @param mixed[] $siteConfig
     * @return mixed[]
     *
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function mapSiteConfigToSettings(array $siteConfig): array
    {
        $settings = [];

        // Simple mappings without special processing
        $simpleMappings = [
            'navigation.gsb-navType' => 'navType',
            'copyright.notice' => 'copyright',
            'search.googleSiteVerification' => 'google_site_verification',
            'colors.background.gsb-background-color-1.color' => 'color_1',
            'colors.background.gsb-background-color-1.label' => 'label_color_1',
            'colors.background.gsb-background-color-2.color' => 'color_2',
            'colors.background.gsb-background-color-2.label' => 'label_color_2',
            'colors.background.gsb-background-color-3.color' => 'color_3',
            'colors.background.gsb-background-color-3.label' => 'label_color_3',
            'colors.background.gsb-background-color-4.color' => 'color_4',
            'colors.background.gsb-background-color-4.label' => 'label_color_4',
            'colors.background.gsb-background-color-5.color' => 'color_5',
            'colors.background.gsb-background-color-5.label' => 'label_color_5',
            'colors.background.gsb-background-color-6.color' => 'color_6',
            'colors.background.gsb-background-color-6.label' => 'label_color_6',
            'colors.colorGeneral.gsb-color-primary' => 'color_primary',
            'colors.colorGeneral.gsb-color-secondary' => 'color_secondary',
            'colors.colorGeneral.gsb-color-secondary-rgba' => 'color_secondary_rgba',
            'colors.colorGeneral.gsb-color-tertiary' => 'color_tertiary',
            'logos.gsb-second-logo-alt' => 'second-logo-alt',
            'logos.gsb-initiative-text' => 'initiative-text',
        ];

        foreach ($simpleMappings as $settingKey => $configKey) {
            $settings = $this->mapOneSiteConfigToSettings($siteConfig, $settingKey, $configKey, $settings);
        }

        // Boolean mappings
        $booleanMappings = [
            'copyright.show-copyright' => 'show-copyright',
            'search.solrEnabledFacets' => 'solr_enabled_facets',
            'search.solrShowTopResults' => 'solrShowTopResults',
            'logos.display-brand-topline' => 'display-brand-topline',
            'search.suche' => 'search',
        ];

        foreach ($booleanMappings as $settingKey => $configKey) {
            $settings = $this->mapOneSiteConfigToSettings($siteConfig, $settingKey, $configKey, $settings, false, true);
        }

        // Convert dot-notation keys to nested array structure
        // This ensures that settings like 'search.suche' become settings['search']['suche']
        // which is required for TypoScript conditions like site('configuration')['settings']['search']['solrEnabledFacets']
        $settings = $this->convertDotNotationToNestedArray($settings);

        // TypoLink mappings
        $typoLinkMappings = [
            'accessability.signLanguagePage' => 'sign-language-page',
            'accessability.simpleLanguagePage' => 'simple-language-page',
            'logos.gsb-logo-big' => 'logo-complete-big',
            'logos.gsb-logo-small' => 'logo-complete-small',
            'logos.gsb-logo-text' => 'logo-text',
            'logos.gsb-second-logo' => 'second-logo',
            'logos.gsb-second-logo-link' => 'second-logo-link',
            'favicons.favicon-96x96-png' => 'favicon-96x96-png',
            'favicons.faviconIco' => 'faviconIco',
            'favicons.faviconSvg' => 'faviconSvg',
            'favicons.apple-touch-icon' => 'apple-touch-icon',
            'favicons.web-app-manifest-192x192' => 'web-app-manifest-192x192',
            'favicons.web-app-manifest-512x512' => 'web-app-manifest-512x512',
            'favicons.webmanifest' => 'webmanifest',
            'fonts.sans.font-sans-name' => 'fonts.sans.font-sans-name',
            'fonts.serif.font-serif-name' => 'fonts.serif.font-serif-name',
            'fonts.sans.font-sans-normal' => 'font-sans-normal',
            'fonts.sans.font-sans-italic' => 'font-sans-italic',
            'fonts.sans.font-sans-medium' => 'font-sans-medium',
            'fonts.sans.font-sans-bold' => 'font-sans-bold',
            'fonts.sans.font-sans-bold-italic' => 'font-sans-bold-italic',
            'fonts.serif.font-serif-normal' => 'font-serif-normal',
            'fonts.serif.font-serif-italic' => 'font-serif-italic',
        ];

        foreach ($typoLinkMappings as $settingKey => $configKey) {
            $settings = $this->mapOneSiteConfigToSettings($siteConfig, $settingKey, $configKey, $settings, true);
        }

        return $settings;
    }

    /**
     * Converts dot-notation keys (e.g., 'search.suche') to nested array structure
     * (e.g., ['search' => ['suche' => ...]])
     *
     * @param mixed[] $settings
     * @return mixed[]
     */
    protected function convertDotNotationToNestedArray(array $settings): array
    {
        $result = [];
        foreach ($settings as $key => $value) {
            if (is_string($key) && str_contains($key, '.')) {
                $keys = explode('.', $key);
                $current = &$result;
                foreach ($keys as $k) {
                    $k = (string)$k;
                    if (!isset($current[$k]) || !is_array($current[$k])) {
                        $current[$k] = [];
                    }
                    $current = &$current[$k];
                }
                $current = $value;
                continue;
            }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * @param mixed[] $parsedTypoScriptConstants
     * @return mixed[]
     *
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function mapConstantsToSettings(array &$parsedTypoScriptConstants): array
    {
        $settings = [];

        $listToMap = [
            'devconfig.debug' => 'config.debug',
            'devconfig.admPanel' => 'config.admPanel',
            'devconfig.noCache' => 'config.no_cache',
            'search.seach-page' => 'config.pids.Search',
            'felogin.emailFrom' => 'styles.content.loginform.emailFrom',
            'felogin.replyToEmail' => 'styles.content.loginform.replyToEmail',
            'devconfig.removeDefaultJS' => 'config.removeDefaultJS',
            'devconfig.compressJs' => 'config.compressJs',
            'devconfig.compressCss' => 'config.compressCss',
            'devconfig.concatenateJs' => 'config.concatenateJs',
            'devconfig.concatenateCss' => 'config.concatenateCss',
            'devconfig.header-comment' => 'config.headerComment',
            'security.spam-protect-email-addresses' => 'config.spamProtectEmailAddresses',
            'security.spam-protect-email-addresses-at-subst' => 'config.spamProtectEmailAddresses_atSubst',
            'navigation.categories-page' => 'config.pids.Categories',
            'navigation.home-page' => 'config.pids.Home',
            'navigation.meta-page' => 'config.pids.Meta',
            'navigation.metaTop-page' => 'config.pids.MetaTop',
            'navigation.footer-page' => 'config.pids.Footer',
            'navigation.header-title' => 'config.headTitle',
            'socialMediaLinks.facebook' => 'config.socialLinks.Facebook',
            'socialMediaLinks.instagram' => 'config.socialLinks.Instagram',
            'socialMediaLinks.youtube' => 'config.socialLinks.YouTube',
            'socialMediaLinks.x' => 'config.socialLinks.Twitter',
        ];

        foreach ($listToMap as $settingKey => $constantKey) {
            $settings = $this->mapOneConstantToSettings($parsedTypoScriptConstants, $settingKey, $constantKey, $settings);
        }

        // templates with default values
        $settings['styles.templates.templateRootPath'] = $parsedTypoScriptConstants['styles.templates.templateRootPath'] ?? 'EXT:gsb_core/Resources/Extensions/fluid_styled_content/Private/Templates';
        $settings['styles.templates.partialRootPath'] = $parsedTypoScriptConstants['styles.templates.partialRootPath'] ?? 'EXT:gsb_core/Resources/Extensions/fluid_styled_content/Private/Partials';
        $settings['styles.templates.layoutRootPath'] = $parsedTypoScriptConstants['styles.templates.layoutRootPath'] ?? 'EXT:gsb_core/Resources/Extensions/fluid_styled_content/Private/Layouts';

        return $settings;
    }
}
