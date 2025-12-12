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

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Console\CommandRegistry;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\TypoScript\TypoScriptStringFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
abstract class AbstractMoveConfigurationToSettings implements UpgradeWizardInterface, ChattyInterface, RepeatableInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    protected ?ConnectionPool $connectionPool = null;
    protected ?SiteFinder $siteFinder = null;
    protected ?TypoScriptStringFactory $typoScriptStringFactory = null;

    public function __construct(?ConnectionPool $connectionPool = null, ?SiteFinder $siteFinder = null, ?TypoScriptStringFactory $typoScriptStringFactory = null)
    {
        $this->connectionPool = $connectionPool;
        $this->siteFinder = $siteFinder;
        $this->typoScriptStringFactory = $typoScriptStringFactory;
    }

    protected function getConnectionPool(): ConnectionPool
    {
        if ($this->connectionPool === null) {
            $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        }
        return $this->connectionPool;
    }

    protected function getSiteFinder(): SiteFinder
    {
        if ($this->siteFinder === null) {
            $this->siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        }
        return $this->siteFinder;
    }

    protected function getTypoScriptStringFactory(): TypoScriptStringFactory
    {
        if ($this->typoScriptStringFactory === null) {
            $this->typoScriptStringFactory = GeneralUtility::makeInstance(TypoScriptStringFactory::class);
        }
        return $this->typoScriptStringFactory;
    }

    /**
     * Returns the title of the upgrade wizard.
     */
    abstract public function getTitle(): string;

    /**
     * Returns the description of the upgrade wizard.
     */
    abstract public function getDescription(): string;

    /**
     * Returns the wizard identifier.
     */
    abstract public function getWizardIdentifier(): string;

    /**
     * Returns the config keys that should be moved to site settings.
     *
     * @return mixed[]
     */
    abstract protected function getConfigKeys(): array;

    /**
     * Maps TypoScript constants to settings structure.
     *
     * @param mixed[] &$parsedTypoScriptConstants
     * @return mixed[]
     */
    abstract protected function mapConstantsToSettings(array &$parsedTypoScriptConstants): array;

    /**
     * Maps site configuration to settings structure.
     *
     * @param mixed[] $siteConfig
     * @return mixed[]
     */
    abstract protected function mapSiteConfigToSettings(array $siteConfig): array;

    /**
     * Checks if the update is necessary for the given site.
     */
    abstract protected function isUpdateNecessaryForSite(string $siteIdentifier, int $siteId): bool;

    /**
     * Executes the update process.
     */
    public function executeUpdate(): bool
    {
        $sites = $this->getSiteFinder()->getAllSites();

        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();
            $this->output->writeln('Processing site: ' . $siteIdentifier);

            $configPath = Environment::getConfigPath() . '/sites/' . $siteIdentifier;
            $settingsFile = $configPath . '/settings.yaml';

            // Read existing settings or create new array
            $existingSettings = [];
            if (file_exists($settingsFile)) {
                $this->output->writeln('Settings file already exists for site: ' . $siteIdentifier);
                $existingSettings = Yaml::parseFile($settingsFile) ?? [];
            }

            // Process site configuration
            $siteConfig = $site->getConfiguration();
            $this->output->writeln('Moving site config to site settings for site: ' . $siteIdentifier);
            $siteSettings = $this->mapSiteConfigToSettings($siteConfig);
            $this->output->writeln('Site settings: ' . print_r($siteSettings, true));

            // Process TypoScript constants
            $parsedTypoScriptConstants = $this->getParsedTypoScriptConstants($site->getRootPageId());
            $this->output->writeln('Moving TypoScript constants to site settings for site: ' . $siteIdentifier);
            $typoScriptSettings = $this->mapConstantsToSettings($parsedTypoScriptConstants);
            $this->output->writeln('TypoScript settings: ' . print_r($typoScriptSettings, true));

            // Merge all settings
            $newSettings = array_merge($existingSettings, $siteSettings, $typoScriptSettings);

            // Write settings file
            try {
                $yaml = Yaml::dump($newSettings, 10, 2);
                $this->output->writeln('Writing settings.yaml for site: ' . $siteIdentifier);
                file_put_contents($settingsFile, $yaml);
            } catch (\Exception $e) {
                $this->output->writeln('Error writing settings.yaml for site: ' . $siteIdentifier . ' - ' . $e->getMessage());
            }

            // Clean up old configuration
            $this->rearrangeSiteConfig($configPath . '/config.yaml');
            $this->removeOldConstants($site->getRootPageId(), $parsedTypoScriptConstants);
        }
        $this->output->writeln('Update completed');
        //flush cache and warmup
        $this->output->writeln('Flushing cache');
        $this->flushCacheOnStateChange();

        return true;
    }

    /**
     * Gets the TypoScript constants from sys_template
     *
     * @param int $siteId
     * @return mixed[]
     *
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function getParsedTypoScriptConstants(int $siteId, bool $debug = true): array
    {
        $config = [];

        try {
            $connection = $this->getConnectionPool()->getConnectionForTable('sys_template');
            $queryBuilder = $connection->createQueryBuilder();

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
                    $astBuilder = GeneralUtility::makeInstance(
                        \TYPO3\CMS\Core\TypoScript\AST\AstBuilder::class,
                        GeneralUtility::makeInstance(\TYPO3\CMS\Core\EventDispatcher\NoopEventDispatcher::class)
                    );
                    $typoScriptTree = $this->getTypoScriptStringFactory()->parseFromString(
                        $row['constants'],
                        $astBuilder
                    );
                    $config = $typoScriptTree->flatten();
                }
            }
            if ($debug) {
                $this->output->writeln('Found TypoScript constants: ' . print_r($config, true));
            }

        } catch (\Exception $e) {
            $this->output->writeln('Error reading TypoScript constants: ' . $e->getMessage());
        }

        return $config;
    }

    /**
     * Removes old configuration from sys_template
     * Removes the specified constant keys from the TypoScript tree
     *
     * @param int $siteId
     * @param mixed[] $parsedTypoScriptConstants
     */
    protected function removeOldConstants(int $siteId, array $parsedTypoScriptConstants): void
    {
        $typoscript = $this->getTyposcriptFromArray($parsedTypoScriptConstants);
        try {
            $connection = $this->getConnectionPool()->getConnectionForTable('sys_template');
            $updateQueryBuilder = $connection->createQueryBuilder();
            $updateQueryBuilder
                ->update('sys_template')
                ->set('constants', $typoscript)
                ->set('include_static_file', '')
                ->set('clear', 0)
                ->where(
                    $updateQueryBuilder->expr()->like('root', $updateQueryBuilder->createNamedParameter(1)),
                    $updateQueryBuilder->expr()->eq('pid', $updateQueryBuilder->createNamedParameter($siteId))
                )
                ->executeQuery();

        } catch (\Exception $e) {
            $this->output->writeln('Error removing specific TypoScript constants: ' . $e->getMessage());
        }
    }

    /**
     * @param mixed[] $parsedTypoScriptConstants
     */
    protected function getTyposcriptFromArray(array $parsedTypoScriptConstants): string
    {
        $typoscript = '';
        foreach ($parsedTypoScriptConstants as $key => $value) {
            $typoscript .= $key . ' = ' . $value . "\n";
        }
        return $typoscript;
    }

    /**
     * @param mixed[] $parsedTypoScriptConstants
     * @param mixed[] $settings
     *
     * @return mixed[]
     */
    public function mapOneConstantToSettings(array &$parsedTypoScriptConstants, string $settingKey, string $constantKey, array $settings = []): array
    {
        if (isset($parsedTypoScriptConstants[$constantKey])) {
            $settings[$settingKey] = $parsedTypoScriptConstants[$constantKey];
            unset($parsedTypoScriptConstants[$constantKey]);
            $this->output->writeln('Unsetting constant: ' . $constantKey);
            $this->output->writeln('Parsed TypoScript Constants: ' . print_r($parsedTypoScriptConstants, true));
        }
        return $settings;
    }

    /**
     * Maps one site config value to settings with optional TypoLink processing
     *
     * @param mixed[] $siteConfig
     * @param mixed[] $settings
     *
     * @return mixed[]
     *
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function mapOneSiteConfigToSettings(array $siteConfig, string $settingKey, string $configKey, array $settings = [], bool $useTypoLink = false, bool $castToBool = false): array
    {
        if (isset($siteConfig[$configKey]) && $siteConfig[$configKey] !== '' && $siteConfig[$configKey] !== 0) {
            if ($useTypoLink) {
                $uid = $this->cutTypolinkToUid($siteConfig[$configKey]);
                if ($uid !== null) {
                    $settings[$settingKey] = $uid;
                }
            } elseif ($castToBool) {
                $settings[$settingKey] = (bool)$siteConfig[$configKey];
            } else {
                $settings[$settingKey] = $siteConfig[$configKey];
            }
        }
        return $settings;
    }

    /**
     * Rearranges site configuration by removing moved keys
     */
    protected function rearrangeSiteConfig(string $configFile): void
    {
        if (!file_exists($configFile)) {
            return;
        }

        $config = Yaml::parseFile($configFile);
        foreach ($this->getConfigKeys() as $key) {
            $this->output->writeln('Removing key from site config: ' . $key);
            unset($config[$key]);
        }
        // set dependencies if not set
        if (!isset($config['dependencies'])) {
            $config['dependencies'] = ['itzbund-gsb/default'];
        }

        $yaml = Yaml::dump($config, 10, 2);
        file_put_contents($configFile, $yaml);
    }

    /**
     * Determines if the update is necessary.
     */
    public function updateNecessary(): bool
    {
        $sites = $this->getSiteFinder()->getAllSites();

        foreach ($sites as $site) {
            $siteIdentifier = $site->getIdentifier();
            if ($this->isUpdateNecessaryForSite($siteIdentifier, $site->getRootPageId())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns an array of identifiers for prerequisite wizards.
     *
     * @return mixed[]
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
        $this->output->writeln('extract uid from typolink: ' . $typolink);
        $typoLink = trim($typolink);

        if (str_contains($typoLink, 't3://page?')) {
            $linkService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\LinkHandling\LinkService::class);
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

    /**
     * Flushes cache using FlushCacheOnStateChangeCommand with group 'all' and random version
     */
    protected function flushCacheOnStateChange(): void
    {
        try {
            $randomVersion = bin2hex(random_bytes(16));
            $this->output->writeln('Executing FlushCacheOnStateChangeCommand with version: ' . $randomVersion);

            $container = GeneralUtility::getContainer();
            $commandRegistry = $container->get(CommandRegistry::class);
            /** @var \ITZBund\GsbClusteredCaching\Command\FlushCacheOnStateChangeCommand $command */
            $command = $commandRegistry->get('gsbclusteredcaching:flushCacheOnStateChange');
            
            $input = new ArrayInput([
                'version' => $randomVersion,
                '--groups' => 'all',
            ]);
            $input->bind($command->getDefinition());
            $output = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL);

            $result = $command->run($input, $output);
            $this->output->writeln('Cache flush command executed with result: ' . $result);
            $this->output->writeln($output->fetch());
        } catch (\Exception $e) {
            $this->output->writeln('Error executing cache flush command: ' . $e->getMessage());
        }
    }
}
