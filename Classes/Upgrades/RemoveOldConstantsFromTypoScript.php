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
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;

/**
 * Upgrade wizard to remove old TypoScript constants that were moved to site settings
 *
 * This wizard should run after MoveConfigurationToSettings and removes the old
 * TypoScript constants that were already migrated to settings.yaml.
 *
 * Can be manually started with
 *
 * `typo3 upgrade:run gsbcore_removeOldConstantsFromTypoScript`
 */
#[UpgradeWizard('gsbcore_removeOldConstantsFromTypoScript')]
class RemoveOldConstantsFromTypoScript extends AbstractMoveConfigurationToSettings
{
    /**
     * TypoScript constant keys that should be removed (mapped from MoveConfigurationToSettings)
     */
    protected const CONSTANT_KEYS_TO_REMOVE = [
        'config.debug',
        'config.admPanel',
        'config.no_cache',
        'config.pids.Search',
        'styles.content.loginform.emailFrom',
        'styles.content.loginform.replyToEmail',
        'config.removeDefaultJS',
        'config.compressJs',
        'config.compressCss',
        'config.concatenateJs',
        'config.concatenateCss',
        'config.headerComment',
        'config.spamProtectEmailAddresses',
        'config.spamProtectEmailAddresses_atSubst',
        'config.pids.Categories',
        'config.pids.Home',
        'config.pids.Meta',
        'config.pids.MetaTop',
        'config.pids.Footer',
        'config.headTitle',
        'config.socialLinks.Facebook',
        'config.socialLinks.Instagram',
        'config.socialLinks.YouTube',
        'config.socialLinks.Twitter',
        'styles.templates.templateRootPath',
        'styles.templates.partialRootPath',
        'styles.templates.layoutRootPath',
    ];

    public function __construct(protected readonly ConnectionPool $connectionPool) {}

    /**
     * Returns the title of the upgrade wizard.
     */
    public function getTitle(): string
    {
        return 'Remove old TypoScript constants';
    }

    /**
     * Returns the description of the upgrade wizard.
     */
    public function getDescription(): string
    {
        return 'Removes old TypoScript constants from sys_template that were already moved to site settings. This wizard must run after MoveConfigurationToSettings.';
    }

    /**
     * Returns the wizard identifier.
     */
    public function getWizardIdentifier(): string
    {
        return 'gsbcore_removeOldConstantsFromTypoScript';
    }

    /**
     * Returns the config keys that should be moved to site settings.
     * Not used in this wizard, but required by abstract class.
     *
     * @return mixed[]
     */
    protected function getConfigKeys(): array
    {
        return [];
    }

    /**
     * Maps TypoScript constants to settings structure.
     * Not used in this wizard, but required by abstract class.
     *
     * @param mixed[] &$parsedTypoScriptConstants
     * @return mixed[]
     */
    protected function mapConstantsToSettings(array &$parsedTypoScriptConstants): array
    {
        return [];
    }

    /**
     * Maps site configuration to settings structure.
     * Not used in this wizard, but required by abstract class.
     *
     * @param mixed[] $siteConfig
     * @return mixed[]
     */
    protected function mapSiteConfigToSettings(array $siteConfig): array
    {
        return [];
    }

    /**
     * Checks if the update is necessary for the given site.
     * Returns true if settings.yaml exists and old constants are still present.
     */
    protected function isUpdateNecessaryForSite(string $siteIdentifier, int $siteId): bool
    {
        // Check if settings.yaml exists (MoveConfigurationToSettings must have run first)
        if (!$this->checkIfSettingsFileExists($siteIdentifier)) {
            return false;
        }

        // Check if old constants are still present
        $parsedTypoScriptConstants = $this->getParsedTypoScriptConstants($siteId, false);

        foreach (self::CONSTANT_KEYS_TO_REMOVE as $constantKey) {
            if (isset($parsedTypoScriptConstants[$constantKey])) {
                return true;
            }
        }

        return false;
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
     * Executes the update process.
     * Removes old TypoScript constants that were already moved to settings.yaml.
     */
    public function executeUpdate(): bool
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();

        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();
            $this->output->writeln('Processing site: ' . $siteIdentifier);

            // Skip if settings.yaml doesn't exist (MoveConfigurationToSettings must run first)
            if (!$this->checkIfSettingsFileExists($siteIdentifier)) {
                $this->output->writeln('Skipping site ' . $siteIdentifier . ': settings.yaml does not exist. Run MoveConfigurationToSettings first.');
                continue;
            }

            // Get current TypoScript constants
            $parsedTypoScriptConstants = $this->getParsedTypoScriptConstants($site->getRootPageId(), false);

            // Remove constants that should be removed
            $constantsToRemove = [];
            foreach (self::CONSTANT_KEYS_TO_REMOVE as $constantKey) {
                if (isset($parsedTypoScriptConstants[$constantKey])) {
                    $constantsToRemove[$constantKey] = $parsedTypoScriptConstants[$constantKey];
                    unset($parsedTypoScriptConstants[$constantKey]);
                    $this->output->writeln('Removing constant: ' . $constantKey);
                }
            }

            // Update sys_template if constants were removed
            if (!empty($constantsToRemove)) {
                $this->removeOldConstants($site->getRootPageId(), $parsedTypoScriptConstants);
            }

            if (empty($constantsToRemove)) {
                $this->output->writeln('No old constants found to remove for site: ' . $siteIdentifier);
            }
        }

        return true;
    }

    /**
     * Returns an array of identifiers for prerequisite wizards.
     *
     * @return mixed[]
     */
    public function getPrerequisites(): array
    {
        return ['gsbcore_moveConfigurationToSettings'];
    }
}
