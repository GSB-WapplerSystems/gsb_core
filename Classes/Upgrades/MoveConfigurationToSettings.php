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
use TYPO3\CMS\Core\EventDispatcher\NoopEventDispatcher;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\TypoScript\AST\AstBuilder;
use TYPO3\CMS\Core\TypoScript\TypoScriptStringFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

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
class MoveConfigurationToSettings implements UpgradeWizardInterface, ChattyInterface, RepeatableInterface
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
        'display-brand-topline',
        'favicon-16x16',
        'favicon-32x32',
        'font-switch',
        'safari-pinned-tab',
        'shortcut-icon',
        'sitePackage',
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
     * Executes the update process.
     */
    public function executeUpdate(): bool
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();

        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();
            $this->output->writeln('Processing site: ' . $siteIdentifier);

            $configPath = Environment::getConfigPath() . '/sites/' . $siteIdentifier;
            $settingsFile = $configPath . '/settings.yaml';

            // Read existing settings or create new array
            if (file_exists($settingsFile)) {
                $this->output->writeln('Settings file already exists for site: ' . $siteIdentifier);
                continue;
            }
            // Process site configuration
            $siteConfig = $site->getConfiguration();

            $this->output->writeln('Moving site config to site settings for site: ' . $siteIdentifier);
            $newSettings = $this->mapSiteConfigToSettings($siteConfig);
            $this->output->writeln('New settings: ' . print_r($newSettings, true));

            // Process TypoScript constants
            $parsedTypoScriptConstants = $this->getParsedTypoScriptConstants($site->getRootPageId());

            $this->output->writeln('Moving TypoScript constants to site settings for site: ' . $siteIdentifier);
            $typoScriptSettings = $this->mapConstantsToSettings($parsedTypoScriptConstants);
            $newSettings = array_merge($newSettings, $typoScriptSettings);

            // Merge with existing settings and write
            try {
                $yaml = Yaml::dump($newSettings, 10, 2);
                $this->output->writeln('Writing settings.yaml for site: ' . $siteIdentifier);
                file_put_contents($settingsFile, $yaml);
            } catch (\Exception $e) {
                $this->output->writeln('Error writing settings.yaml for site: ' . $siteIdentifier . ' - ' . $e->getMessage());
            }
            $this->removeKeysFromSiteConfig($configPath . '/config.yaml');
            $this->removeOldConfiguration($site->getRootPageId());

        }

        return true;
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
        // Map specific config.yaml values to settings.yaml structure
        $settings = [];
        if (isset($siteConfig['navType']) && $siteConfig['navType'] != 0) {
            $settings['navigation.gsb-navType'] = $siteConfig['navType'];
        }
        if (isset($siteConfig['copyright']) && $siteConfig['copyright'] != '') {
            $settings['copyright.notice'] = $siteConfig['copyright'];
        }
        if (isset($siteConfig['show-copyright'])) {
            $settings['copyright.show-copyright'] = (bool)($siteConfig['show-copyright']);
        }
        if (isset($siteConfig['google_site_verification']) && $siteConfig['google_site_verification'] != '') {
            $settings['search.googleSiteVerification'] = $siteConfig['google_site_verification'];
        }
        if (isset($siteConfig['solr_enabled_facets']) && $siteConfig['solr_enabled_facets'] != '') {
            $settings['search.solrEnabledFacets'] = (bool)($siteConfig['solr_enabled_facets']);
        }
        if (isset($siteConfig['solrShowTopResults']) && $siteConfig['solrShowTopResults'] != '') {
            $settings['search.solrShowTopResults'] = (bool)($siteConfig['solrShowTopResults']);
        }
        if (isset($siteConfig['search']) && $siteConfig['search'] != '') {
            $settings['search.suche'] = (bool)($siteConfig['search']);
        }

        if (isset($siteConfig['sign-language-page']) && $siteConfig['sign-language-page'] != '') {
            $pageUid = $this->cutTypolinkToUid($siteConfig['sign-language-page']);
            $settings['accessability.signLanguagePage'] = $pageUid;
        }
        if (isset($siteConfig['simple-language-page']) && $siteConfig['simple-language-page'] != '') {
            $pageUid = $this->cutTypolinkToUid($siteConfig['simple-language-page']);
            $settings['accessability.simpleLanguagePage'] = $pageUid;
        }

        if (isset($siteConfig['color_1']) && $siteConfig['color_1'] != '') {
            $settings['colors.background.gsb-background-color-1'] = $siteConfig['color_1'];
        }
        if (isset($siteConfig['label_color_1']) && $siteConfig['label_color_1'] != '') {
            $settings['colors.background.gsb-background-color-1.label'] = $siteConfig['label_color_1'];
        }
        if (isset($siteConfig['color_2']) && $siteConfig['color_2'] != '') {
            $settings['colors.background.gsb-background-color-2'] = $siteConfig['color_2'];
        }
        if (isset($siteConfig['label_color_2']) && $siteConfig['label_color_2'] != '') {
            $settings['colors.background.gsb-background-color-2.label'] = $siteConfig['label_color_2'];
        }
        if (isset($siteConfig['color_3']) && $siteConfig['color_3'] != '') {
            $settings['colors.background.gsb-background-color-3'] = $siteConfig['color_3'];
        }
        if (isset($siteConfig['label_color_3']) && $siteConfig['label_color_3'] != '') {
            $settings['colors.background.gsb-background-color-3.label'] = $siteConfig['label_color_3'];
        }
        if (isset($siteConfig['color_4']) && $siteConfig['color_4'] != '') {
            $settings['colors.background.gsb-background-color-4'] = $siteConfig['color_4'];
        }
        if (isset($siteConfig['label_color_4']) && $siteConfig['label_color_4'] != '') {
            $settings['colors.background.gsb-background-color-4.label'] = $siteConfig['label_color_4'];
        }
        if (isset($siteConfig['color_5']) && $siteConfig['color_5'] != '') {
            $settings['colors.background.gsb-background-color-5'] = $siteConfig['color_5'];
        }
        if (isset($siteConfig['label_color_5']) && $siteConfig['label_color_5'] != '') {
            $settings['colors.background.gsb-background-color-5.label'] = $siteConfig['label_color_5'];
        }
        if (isset($siteConfig['color_6']) && $siteConfig['color_6'] != '') {
            $settings['colors.background.gsb-background-color-6'] = $siteConfig['color_6'];
        }
        if (isset($siteConfig['label_color_6']) && $siteConfig['label_color_6'] != '') {
            $settings['colors.background.gsb-background-color-6.label'] = $siteConfig['label_color_6'];
        }
        if (isset($siteConfig['color_primary']) && $siteConfig['color_primary'] != '') {
            $settings['colors.colorGeneral.gsb-color-primary'] = $siteConfig['color_primary'];
        }
        if (isset($siteConfig['color_secondary']) && $siteConfig['color_secondary'] != '') {
            $settings['colors.colorGeneral.gsb-color-secondary'] = $siteConfig['color_secondary'];
        }
        if (isset($siteConfig['color_secondary_rgba']) && $siteConfig['color_secondary_rgba'] != '') {
            $settings['colors.colorGeneral.gsb-color-secondary-rgba'] = $siteConfig['color_secondary_rgba'];
        }
        if (isset($siteConfig['color_tertiary']) && $siteConfig['color_tertiary'] != '') {
            $settings['colors.colorGeneral.gsb-color-tertiary'] = $siteConfig['color_tertiary'];
        }
        if (isset($siteConfig['logo-complete-toggle'])) {
            if (isset($siteConfig['logo-complete-big']) && $siteConfig['logo-complete-big'] != '') {
                $fileUid = $this->cutTypolinkToUid($siteConfig['logo-complete-big']);
                $settings['logos.gsb-logo-big'] = $fileUid;
            }
            if (isset($siteConfig['logo-complete-small']) && $siteConfig['logo-complete-small'] != '') {
                $fileUid = $this->cutTypolinkToUid($siteConfig['logo-complete-small']);
                $settings['logos.gsb-logo-small'] = $fileUid;
            }
        }

        if (isset($siteConfig['logo-text']) && $siteConfig['logo-text'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['logo-text']);
            $settings['logos.gsb-logo-text'] = $fileUid;
        }
        if (isset($siteConfig['second-logo-complete-toggle'])) {
            if (isset($siteConfig['second-logo']) && $siteConfig['second-logo'] != '') {
                $fileUid = $this->cutTypolinkToUid($siteConfig['second-logo']);
                $settings['logos.gsb-second-logo'] = $fileUid;
            }
            if (isset($siteConfig['second-logo-alt']) && $siteConfig['second-logo-alt'] != '') {
                $settings['logos.gsb-second-logo-alt'] = $siteConfig['second-logo-alt'];
            }
            if (isset($siteConfig['second-logo-link']) && $siteConfig['second-logo-link'] != '') {
                $pageUid = $this->cutTypolinkToUid($siteConfig['second-logo-link']);
                $settings['logos.gsb-second-logo-link'] = $pageUid;
            }
        }

        if (isset($siteConfig['initiative-text-toggle'])) {
            if (isset($siteConfig['initiative-text']) && $siteConfig['initiative-text'] != '') {
                $settings['logos.gsb-initiative-text'] = $siteConfig['initiative-text'];
            }
        }

        if (isset($siteConfig['favicon-96x96-png']) && $siteConfig['favicon-96x96-png'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['favicon-96x96-png']);
            $settings['favicons.favicon-96x96-png'] = $fileUid;
        }
        if (isset($siteConfig['faviconIco']) && $siteConfig['faviconIco'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['faviconIco']);
            $settings['favicons.faviconIco'] = $fileUid;
        }
        if (isset($siteConfig['faviconSvg']) && $siteConfig['faviconSvg'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['faviconSvg']);
            $settings['favicons.faviconSvg'] = $fileUid;
        }
        if (isset($siteConfig['apple-touch-icon']) && $siteConfig['apple-touch-icon'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['apple-touch-icon']);
            $settings['favicons.apple-touch-icon'] = $fileUid;
        }
        if (isset($siteConfig['web-app-manifest-192x192']) && $siteConfig['web-app-manifest-192x192'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['web-app-manifest-192x192']);
            $settings['favicons.web-app-manifest-192x192'] = $fileUid;
        }
        if (isset($siteConfig['web-app-manifest-512x512']) && $siteConfig['web-app-manifest-512x512'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['web-app-manifest-512x512']);
            $settings['favicons.web-app-manifest-512x512'] = $fileUid;
        }
        if (isset($siteConfig['webmanifest']) && $siteConfig['webmanifest'] != '') {
            $fileUid = $this->cutTypolinkToUid($siteConfig['webmanifest']);
            $settings['favicons.webmanifest'] = $fileUid;
        }
        return $settings;
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
    protected function mapConstantsToSettings(array $parsedTypoScriptConstants): array
    {
        $settings = [];

        // dev config
        if (isset($parsedTypoScriptConstants['config.debug'])) {
            $settings['devconfig.debug'] = $parsedTypoScriptConstants['config.debug'];
        }
        if (isset($parsedTypoScriptConstants['config.admPanel'])) {
            $settings['devconfig.admPanel'] = $parsedTypoScriptConstants['config.admPanel'];
        }
        if (isset($parsedTypoScriptConstants['config.noCache'])) {
            $settings['devconfig.noCache'] = $parsedTypoScriptConstants['config.no_cache'];
        }
        if (isset($parsedTypoScriptConstants['config.removeDefaultJS'])) {
            $settings['devconfig.removeDefaultJS'] = $parsedTypoScriptConstants['config.removeDefaultJS'];
        }
        if (isset($parsedTypoScriptConstants['config.compressJs'])) {
            $settings['devconfig.compressJs'] = $parsedTypoScriptConstants['config.compressJs'];
        }
        if (isset($parsedTypoScriptConstants['config.compressCss'])) {
            $settings['devconfig.compressCss'] = $parsedTypoScriptConstants['config.compressCss'];
        }
        if (isset($parsedTypoScriptConstants['config.concatenateJs'])) {
            $settings['devconfig.concatenateJs'] = $parsedTypoScriptConstants['config.concatenateJs'];
        }
        if (isset($parsedTypoScriptConstants['config.concatenateCss'])) {
            $settings['devconfig.concatenateCss'] = $parsedTypoScriptConstants['config.concatenateCss'];
        }
        if (isset($parsedTypoScriptConstants['config.headerComment'])) {
            $settings['devconfig.header-comment'] = $parsedTypoScriptConstants['config.headerComment'];
        }
        // security
        if (isset($parsedTypoScriptConstants['config.spamProtectEmailAddresses'])) {
            $settings['security.spam-protect-email-addresses'] = $parsedTypoScriptConstants['config.spamProtectEmailAddresses'];
        }
        if (isset($parsedTypoScriptConstants['config.spamProtectEmailAddresses_atSubst'])) {
            $settings['security.spam-protect-email-addresses-at-subst'] = $parsedTypoScriptConstants['config.spamProtectEmailAddresses_atSubst'];
        }
        // pids
        if (isset($parsedTypoScriptConstants['config.pids.Categories'])) {
            $settings['navigation.categories-page'] = $parsedTypoScriptConstants['config.pids.Categories'];
        }
        if (isset($parsedTypoScriptConstants['config.pids.Home'])) {
            $settings['navigation.home-page'] = $parsedTypoScriptConstants['config.pids.Home'];
        }
        if (isset($parsedTypoScriptConstants['config.pids.Meta'])) {
            $settings['navigation.meta-page'] = $parsedTypoScriptConstants['config.pids.Meta'];
        }
        if (isset($parsedTypoScriptConstants['config.pids.MetaTop'])) {
            $settings['navigation.metaTop-page'] = $parsedTypoScriptConstants['config.pids.MetaTop'];
        }
        if (isset($parsedTypoScriptConstants['config.pids.Footer'])) {
            $settings['navigation.footer-page'] = $parsedTypoScriptConstants['config.pids.Footer'];
        }
        // navigation
        if (isset($parsedTypoScriptConstants['config.headTitle'])) {
            $settings['navigation.header-title'] = $parsedTypoScriptConstants['config.headTitle'];
        }

        // templates
        $settings['styles.templates.templateRootPath'] = $parsedTypoScriptConstants['styles.templates.templateRootPath'] ?? 'EXT:gsb_core/Resources/Extensions/fluid_styled_content/Private/Templates';
        $settings['styles.templates.partialRootPath'] = $parsedTypoScriptConstants['styles.templates.partialRootPath'] ?? 'EXT:gsb_core/Resources/Extensions/fluid_styled_content/Private/Partials';
        $settings['styles.templates.layoutRootPath'] = $parsedTypoScriptConstants['styles.templates.layoutRootPath'] ?? 'EXT:gsb_core/Resources/Extensions/fluid_styled_content/Private/Layouts';

        return $settings;
    }

    /**
     * Gets the TypoScript constants from sys_template
     * @param int $siteId
     * @return mixed[]
     *
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function getParsedTypoScriptConstants(int $siteId): array
    {
        $config = [];
        $TypoScriptFactory = GeneralUtility::makeInstance(TypoScriptStringFactory::class);
        try {
            $connection = $this->connectionPool->getConnectionForTable('sys_template');
            $queryBuilder = $connection->createQueryBuilder();
            // Get constants from sys_template table
            $result = $queryBuilder
                ->select('constants')
                ->from('sys_template')
                ->where(
                    $queryBuilder->expr()->like('root', $queryBuilder->createNamedParameter(1)),
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($siteId))
                )
                ->executeQuery();

            while ($row = $result->fetchAssociative()) {
                if (!empty($row['constants'])) {
                    $typoScriptTree = $TypoScriptFactory->parseFromString($row['constants'], new AstBuilder(new NoopEventDispatcher()));
                    $config = $typoScriptTree->flatten();
                }
            }
            $this->output->writeln('Found TypoScript constants: ' . print_r($config, true));

        } catch (\Exception $e) {
            $this->output->writeln('Error reading TypoScript constants: ' . $e->getMessage());
        }

        return $config;
    }

    protected function removeOldConfiguration(int $siteId): void
    {
        try {
            $connection = $this->connectionPool->getConnectionForTable('sys_template');
            $queryBuilder = $connection->createQueryBuilder();
            // Get constants from sys_template table
            $queryBuilder
                ->update('sys_template')
                ->set('constants', '')
                ->set('include_static_file', '')
                ->set('clear', 0)
                ->where(
                    $queryBuilder->expr()->like('root', $queryBuilder->createNamedParameter(1)),
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($siteId))
                )
                ->executeQuery();
        } catch (\Exception $e) {
            $this->output->writeln('Error removing TypoScript constants: ' . $e->getMessage());
        }
    }

    /**
     * Determines if the update is necessary.
     */
    public function updateNecessary(): bool
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();

        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();

            // Check if settings.yaml doesn't exist
            if (!$this->checkIfSettingsFileExists($siteIdentifier)) {
                return true;
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

    protected function rearangeSiteConfig(string $configFile, string $key): void
    {
        $this->output->writeln('removing key from site config: ' . $key);
        // remove key from site config in config.yaml
        $config = Yaml::parseFile($configFile);
        unset($config[$key]);
        $config['dependencies'] = ['itzbund-gsb/default'];
        $yaml = Yaml::dump($config, 10, 2);
        file_put_contents($configFile, $yaml);
    }

    protected function removeKeysFromSiteConfig(string $configFile): void
    {
        $config = Yaml::parseFile($configFile);
        foreach (self::CONFIG_KEYS as $key) {
            unset($config[$key]);
        }

        $yaml = Yaml::dump($config, 10, 2);
        file_put_contents($configFile, $yaml);
    }
}
