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

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * More accurate database:updateschema --destructive
 *
 * Can be manually started with
 *
 * `typo3 upgrade:run gsbcore_removeColumnsWizard`
 */
#[UpgradeWizard('gsbcore_moveSiteConfigToSiteSettingsWizard')]
class MoveSiteConfigToSiteSettingsWizard implements UpgradeWizardInterface, ChattyInterface, RepeatableInterface
{


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
    ];
    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(protected readonly ConnectionPool $connectionPool) {}

    /**
     * Returns the title of the upgrade wizard.
     */
    public function getTitle(): string
    {
        return 'move site config to site settings';
    }

    /**
     * Returns the description of the upgrade wizard.
     */
    public function getDescription(): string
    {
        return 'if Settings.ymal dose not exist, it will be created, the content of site_config will be moved to it';
    }

    /**
     * Executes the update process.
     */
    public function executeUpdate(): bool
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();
            if (!$this->checkIfSettingsFileExists($siteIdentifier)) {
                $siteConfig = $site->getConfiguration();
                $configPath = Environment::getConfigPath() . '/sites/' . $siteIdentifier;
                $configFile = $configPath . '/config.yaml';
                $this->output->writeln('Moving site config to site settings for site: ' . $siteIdentifier);
                $newSettings = $this->mapSiteConfigToSettings($siteConfig, $configFile);
                $yaml = Yaml::dump($newSettings, 10, 4);
                $settingsFile = $configPath . '/settings.yaml';
                $this->output->writeln('Writing settings.yaml for site: ' . $siteIdentifier);
                file_put_contents($settingsFile, $yaml);
                $this->removeEmptyKeysFromSiteConfig($configFile);
            }
        }

        return true;
    }

    protected function mapSiteConfigToSettings(array $siteConfig, string $configFile): array
    {
        // Map specific config.yaml values to settings.yaml structure
        $settings = [];
        if (isset($siteConfig['navType']) && $siteConfig['navType'] != 0) {
            $settings['navigation.gsb-navType'] = $siteConfig['navType'];
        }
        if (isset($siteConfig['copyright']) && $siteConfig['copyright'] != '') {
            $settings['copyright.notice'] = $siteConfig['copyright'];
            $this->removeKeyFromSiteConfig($configFile, 'copyright');
        }
        if (isset($siteConfig['show-copyright'])) {
            $settings['copyright.show-copyright'] = (bool)($siteConfig['show-copyright']);
            $this->removeKeyFromSiteConfig($configFile, 'show-copyright');
        }
        if (isset($siteConfig['google_site_verification']) && $siteConfig['google_site_verification'] != '') {
            $settings['search.googleSiteVerification'] = $siteConfig['google_site_verification'];
            $this->removeKeyFromSiteConfig($configFile, 'google_site_verification');
        }
        if (isset($siteConfig['solr_enabled_facets']) && $siteConfig['solr_enabled_facets'] != '') {
            $settings['search.solrEnabledFacets'] = (bool)($siteConfig['solr_enabled_facets']);
            $this->removeKeyFromSiteConfig($configFile, 'solr_enabled_facets');
        }
        if (isset($siteConfig['solrShowTopResults']) && $siteConfig['solrShowTopResults'] != '') {
            $settings['search.solrShowTopResults'] = (bool)($siteConfig['solrShowTopResults']);
            $this->removeKeyFromSiteConfig($configFile, 'solrShowTopResults');
        }
        if (isset($siteConfig['search']) && $siteConfig['search'] != '') {
            $settings['search.suche'] = (bool)($siteConfig['search']);
            $this->removeKeyFromSiteConfig($configFile, 'search');
        }

        if (isset($siteConfig['sign-language-page']) && $siteConfig['sign-language-page'] != '') {
            $pageUid = $this->cutTypolinkToUid($siteConfig['sign-language-page']);
            $settings['accessability.signLanguagePage'] = $pageUid;
            $this->removeKeyFromSiteConfig($configFile, 'sign-language-page');
        }
        if (isset($siteConfig['simple-language-page']) && $siteConfig['simple-language-page'] != '') {
            $pageUid = $this->cutTypolinkToUid($siteConfig['simple-language-page']);
            $settings['accessability.simpleLanguagePage'] = $pageUid;
            $this->removeKeyFromSiteConfig($configFile, 'simple-language-page');
        }

        if (isset($siteConfig['color_1']) && $siteConfig['color_1'] != '') {
            $settings['colors.background.gsb-background-color-1'] = $siteConfig['color_1'];
            $this->removeKeyFromSiteConfig($configFile, 'color_1');
        }
        if (isset($siteConfig['label_color_1']) && $siteConfig['label_color_1'] != '') {
            $settings['colors.background.gsb-background-color-1.label'] = $siteConfig['label_color_1'];
            $this->removeKeyFromSiteConfig($configFile, 'label_color_1');
        }
        if (isset($siteConfig['color_2']) && $siteConfig['color_2'] != '') {
            $settings['colors.background.gsb-background-color-2'] = $siteConfig['color_2'];
            $this->removeKeyFromSiteConfig($configFile, 'color_2');
        }
        if (isset($siteConfig['label_color_2']) && $siteConfig['label_color_2'] != '') {
            $settings['colors.background.gsb-background-color-2.label'] = $siteConfig['label_color_2'];
            $this->removeKeyFromSiteConfig($configFile, 'label_color_2');
        }
        if (isset($siteConfig['color_3']) && $siteConfig['color_3'] != '') {
            $settings['colors.background.gsb-background-color-3'] = $siteConfig['color_3'];
            $this->removeKeyFromSiteConfig($configFile, 'color_3');
        }
        if (isset($siteConfig['label_color_3']) && $siteConfig['label_color_3'] != '') {
            $settings['colors.background.gsb-background-color-3.label'] = $siteConfig['label_color_3'];
            $this->removeKeyFromSiteConfig($configFile, 'label_color_3');
        }
        if (isset($siteConfig['color_4']) && $siteConfig['color_4'] != '') {
            $settings['colors.background.gsb-background-color-4'] = $siteConfig['color_4'];
            $this->removeKeyFromSiteConfig($configFile, 'color_4');
        }
        if (isset($siteConfig['label_color_4']) && $siteConfig['label_color_4'] != '') {
            $settings['colors.background.gsb-background-color-4.label'] = $siteConfig['label_color_4'];
            $this->removeKeyFromSiteConfig($configFile, 'label_color_4');
        }
        if (isset($siteConfig['color_5']) && $siteConfig['color_5'] != '') {
            $settings['colors.background.gsb-background-color-5'] = $siteConfig['color_5'];
            $this->removeKeyFromSiteConfig($configFile, 'color_5');
        }
        if (isset($siteConfig['label_color_5']) && $siteConfig['label_color_5'] != '') {
            $settings['colors.background.gsb-background-color-5.label'] = $siteConfig['label_color_5'];
            $this->removeKeyFromSiteConfig($configFile, 'label_color_5');
        }
        if (isset($siteConfig['color_6']) && $siteConfig['color_6'] != '') {
            $settings['colors.background.gsb-background-color-6'] = $siteConfig['color_6'];
            $this->removeKeyFromSiteConfig($configFile, 'color_6');
        }
        if (isset($siteConfig['label_color_6']) && $siteConfig['label_color_6'] != '') {
            $settings['colors.background.gsb-background-color-6.label'] = $siteConfig['label_color_6'];
            $this->removeKeyFromSiteConfig($configFile, 'label_color_6');
        }
        if (isset($siteConfig['color_primary']) && $siteConfig['color_primary'] != '') {
            $settings['colors.colorGeneral.gsb-color-primary'] = $siteConfig['color_primary'];
            $this->removeKeyFromSiteConfig($configFile, 'color_primary');
        }
        if (isset($siteConfig['color_secondary']) && $siteConfig['color_secondary'] != '') {
            $settings['colors.colorGeneral.gsb-color-secondary'] = $siteConfig['color_secondary'];
            $this->removeKeyFromSiteConfig($configFile, 'color_secondary');
        }
        if (isset($siteConfig['color_secondary_rgba']) && $siteConfig['color_secondary_rgba'] != '') {
            $settings['colors.colorGeneral.gsb-color-secondary-rgba'] = $siteConfig['color_secondary_rgba'];
            $this->removeKeyFromSiteConfig($configFile, 'color_secondary_rgba');
        }
        if (isset($siteConfig['color_tertiary']) && $siteConfig['color_tertiary'] != '') {
            $settings['colors.colorGeneral.gsb-color-tertiary'] = $siteConfig['color_tertiary'];
            $this->removeKeyFromSiteConfig($configFile, 'color_tertiary');
        }
        if (isset($siteConfig['logo-complete-toggle'])) {
            $this->removeKeyFromSiteConfig($configFile, 'logo-complete-toggle');
            if (isset($siteConfig['logo-complete-big']) && $siteConfig['logo-complete-big'] != '') {
                $fileUid = $this->cutTypolinkToUid($siteConfig['logo-complete-big']);
                $settings['logos.gsb-logo-complete-big'] = $fileUid;
                $this->removeKeyFromSiteConfig($configFile, 'logo-complete-big');
            }
            if (isset($siteConfig['logo-complete-small']) && $siteConfig['logo-complete-small'] != '') {
                $fileUid = $this->cutTypolinkToUid($siteConfig['logo-complete-small']);
                $settings['logos.gsb-logo-complete-small'] = $fileUid;
                $this->removeKeyFromSiteConfig($configFile, 'logo-complete-small');
            }
        }

        if (isset($siteConfig['logo-text']) && $siteConfig['logo-text'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['logo-text']);
            $settings['logos.gsb-logo-text'] = $fileUid;
            $this->removeKeyFromSiteConfig($configFile, 'logo-text');
        }
        if (isset($siteConfig['second-logo-complete-toggle'])) {
            $this->removeKeyFromSiteConfig($configFile, 'second-logo-complete-toggle');
            if (isset($siteConfig['second-logo']) && $siteConfig['second-logo'] != '') {
                $fileUid = $this->cutTypolinkToUid($siteConfig['second-logo']);
                $settings['logos.gsb-second-logo'] = $fileUid;
                $this->removeKeyFromSiteConfig($configFile, 'second-logo');
            }
            if (isset($siteConfig['second-logo-alt']) && $siteConfig['second-logo-alt'] != '') {
                $settings['logos.gsb-second-logo-alt'] = $siteConfig['second-logo-alt'];
                $this->removeKeyFromSiteConfig($configFile, 'second-logo-alt');
            }
            if (isset($siteConfig['second-logo-link']) && $siteConfig['second-logo-link'] != '') {
                $pageUid = $this->cutTypolinkToUid($siteConfig['second-logo-link']);
                $settings['logos.gsb-second-logo-link'] = $pageUid;
                $this->removeKeyFromSiteConfig($configFile, 'second-logo-link');
            }
        }

        if (isset($siteConfig['initiative-text-toggle'])) {
            $this->removeKeyFromSiteConfig($configFile, 'initiative-text-toggle');
            if (isset($siteConfig['initiative-text']) && $siteConfig['initiative-text'] != '') {
                $settings['logos.gsb-initiative-text'] = $siteConfig['initiative-text'];
                $this->removeKeyFromSiteConfig($configFile, 'initiative-text');
            }
        }

        if (isset($siteConfig['favicon-96x96-png']) && $siteConfig['favicon-96x96-png'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['favicon-96x96-png']);
            $settings['favicons.favicon-96x96-png'] = $fileUid;
            $this->removeKeyFromSiteConfig($configFile, 'favicon-96x96-png');
        }
        if (isset($siteConfig['faviconIco']) && $siteConfig['faviconIco'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['faviconIco']);
            $settings['favicons.faviconIco'] = $fileUid;
            $this->removeKeyFromSiteConfig($configFile, 'faviconIco');
        }
        if (isset($siteConfig['faviconSvg']) && $siteConfig['faviconSvg'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['faviconSvg']);
            $settings['favicons.faviconSvg'] = $fileUid;
            $this->removeKeyFromSiteConfig($configFile, 'faviconSvg');
        }
        if (isset($siteConfig['apple-touch-icon']) && $siteConfig['apple-touch-icon'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['apple-touch-icon']);
            $settings['favicons.apple-touch-icon'] = $fileUid;
            $this->removeKeyFromSiteConfig($configFile, 'apple-touch-icon');
        }
        if (isset($siteConfig['web-app-manifest-192x192']) && $siteConfig['web-app-manifest-192x192'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['web-app-manifest-192x192']);
            $settings['favicons.web-app-manifest-192x192'] = $fileUid;
            $this->removeKeyFromSiteConfig($configFile, 'web-app-manifest-192x192');
        }
        if (isset($siteConfig['web-app-manifest-512x512']) && $siteConfig['web-app-manifest-512x512'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['web-app-manifest-512x512']);
            $settings['favicons.web-app-manifest-512x512'] = $fileUid;
            $this->removeKeyFromSiteConfig($configFile, 'web-app-manifest-512x512');
        }
        if (isset($siteConfig['webmanifest']) && $siteConfig['webmanifest'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['webmanifest']);
            $settings['favicons.webmanifest'] = $fileUid;
            $this->removeKeyFromSiteConfig($configFile, 'webmanifest');
        }

        return $settings;
    }

    /**
     * Determines if the update is necessary.
     */
    public function updateNecessary(): bool
    {
        //check if settings.yaml exists
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();
            if (!$this->checkIfSettingsFileExists($siteIdentifier)) {
                return true;
            }
            foreach (self::CONFIG_KEYS as $key) {
                if (isset($siteConfig[$key]) && $siteConfig[$key] != '') {
                    return true;
                }
            }
        }
        return false;
    }

    protected function checkIfSettingsFileExists(string $siteIdentifier): bool
    {
        $configPath = Environment::getConfigPath() . '/sites/' . $siteIdentifier;
        $settingsFile = $configPath . '/settings.yaml';
        return file_exists($settingsFile);
    }

    /**
     * Returns an array of identifiers for prerequisite wizards.
     */
    public function getPrerequisites(): array
    {
        return [];
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    protected function cutTypolinkToUid(string $typolink): ?int
    {
        $linkService = GeneralUtility::makeInstance(LinkService::class);
        $this->output->writeln('extract uid from typolink: ' . $typolink);
        $typoLink = trim($typolink);

        if (str_contains($typoLink, 't3://page?')) {
            $linkData = $linkService->resolve(trim($typoLink));
            $this->output->writeln('linkData: ' . json_encode($linkData));

            return $linkData['pageuid'];
        }
        if (str_contains($typoLink, 't3://file?')) {
            if (preg_match('/uid=(\d+)/', $typoLink, $matches)) {
                $this->output->writeln('linkData: ' . json_encode($matches));
                return (int)$matches[1];
            }
        }

        $this->output->writeln('no uid found in typolink');
        return null;
    }

    protected function removeKeyFromSiteConfig(string $configFile, string $key): void
    {
        $this->output->writeln('removing key from site config: ' . $key);
        // remove key from site config in config.yaml
        $config = Yaml::parseFile($configFile);
        unset($config[$key]);
        $yaml = Yaml::dump($config, 10, 4);
        file_put_contents($configFile, $yaml);
    }

    protected function removeEmptyKeysFromSiteConfig(string $configFile): void
    {
        $config = Yaml::parseFile($configFile);
        $config = array_filter($config, function ($value) {
            return $value !== '';
        });
        unset($config['sitePackage']);
        unset($config['font-switch']);
        unset($config['display-brand-topline']);
        unset($config['navType']);
        $yaml = Yaml::dump($config, 10, 4);
        file_put_contents($configFile, $yaml);
    }
}
