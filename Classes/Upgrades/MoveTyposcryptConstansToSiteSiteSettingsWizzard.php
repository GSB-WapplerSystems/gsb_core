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
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\TypoScript\AST\AstBuilder;
use TYPO3\CMS\Core\TypoScript\TypoScriptStringFactory;
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
 * `typo3 upgrade:run gsbcore_moveTypoScriptConstantsToSiteSettingsWizard`
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.LongClassName)
 */
#[UpgradeWizard('gsbcore_moveTypoScriptConstantsToSiteSettingsWizard')]
class MoveTyposcryptConstansToSiteSiteSettingsWizzard implements UpgradeWizardInterface, ChattyInterface, RepeatableInterface
{
    /**
     * Constants that should be moved from extension configuration to site settings
     */
    protected const CONSTANT_KEYS = [
        'config.no_cache',
        'config.compressJs',
        'config.compressCss',
        'con',
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
        return 'Move TypoScript constants to site settings';
    }

    /**
     * Returns the description of the upgrade wizard.
     */
    public function getDescription(): string
    {
        return 'Moves TypoScript constants from extension configuration to site settings. If settings.yaml does not exist, it will be created.';
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

            // Get extension configuration
            $parsedTypoScriptConstants = $this->getParsedTypoScriptConstants($site->getRootPageId());
            if (!empty($parsedTypoScriptConstants)) {
                $configPath = Environment::getConfigPath() . '/sites/' . $siteIdentifier;
                $settingsFile = $configPath . '/settings.yaml';

                // Read existing settings or create new array
                $existingSettings = [];
                if (file_exists($settingsFile)) {
                    $existingSettings = Yaml::parseFile($settingsFile) ?? [];
                }

                // Map constants to settings
                $newSettings = $this->mapConstantsToSettings($parsedTypoScriptConstants);

                // Merge with existing settings
                // note: if a setting exist ist will be convertet to an array so its importent to run this only onece
                $mergedSettings = array_merge_recursive($existingSettings, $newSettings);

                // Write settings file
                $yaml = Yaml::dump($mergedSettings, 10, 4);
                $this->output->writeln('Writing settings.yaml for site: ' . $siteIdentifier);
                file_put_contents($settingsFile, $yaml);
                $this->removeOldConfiguration($site->getRootPageId());

                $this->output->writeln('Constants migrated to site settings. Original extension configuration preserved.');
            }
        }

        return true;
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
        if (isset($parsedTypoScriptConstants['styles.templates.templateRootPath'])) {
            $settings['styles.templates.templateRootPath'] = $parsedTypoScriptConstants['styles.templates.templateRootPath'];
        }
        if (isset($parsedTypoScriptConstants['styles.templates.partialRootPath'])) {
            $settings['styles.templates.partialRootPath'] = $parsedTypoScriptConstants['styles.templates.partialRootPath'];
        }
        if (isset($parsedTypoScriptConstants['styles.templates.layoutRootPath'])) {
            $settings['styles.templates.layoutRootPath'] = $parsedTypoScriptConstants['styles.templates.layoutRootPath'];
        }

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
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
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

    /**
     * Determines if the update is necessary.
     */
    public function updateNecessary(): bool
    {
        //run if settings.yaml dont exists
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();
            if ($this->checkIfSettingsFileExists($siteIdentifier)) {
                return true;
            }
        }
        // run if there are any constants in sys_template
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();
            if ($this->checkIfSettingsFileExists($siteIdentifier)) {
                return true;
            }
        }
        // check if mapping is already done
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();
            if ($this->checkIfSettingsFileExists($siteIdentifier)) {
                $parsedTypoScriptConstants = $this->getParsedTypoScriptConstants($site->getRootPageId());
                if (!empty($parsedTypoScriptConstants)) {
                    $settings = $this->mapConstantsToSettings($parsedTypoScriptConstants);
                    if (!empty($settings)) {
                        return true;
                    }
                }
            }
        }
        return false;
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

    protected function checkIfSettingsFileExists(string $siteIdentifier): bool
    {
        $configPath = Environment::getConfigPath() . '/sites/' . $siteIdentifier;
        $settingsFile = $configPath . '/settings.yaml';
        return file_exists($settingsFile);
    }
}
