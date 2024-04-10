<?php

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This command imports a configuration from a yaml file.
 * It is used to import the configuration of the workspaces and be_groups tables.
 * Store the configuration for Workspaces Configuration/Workspaces/*.yaml
 * Store the configuration for be_groups Configuration/BackendUserGroups/*.yaml
 */
class ConfigurationImportCommand extends Command
{
    private \TYPO3\CMS\Core\Package\PackageManager $packageManager;
    private \TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader $yamlFileLoader;

    public function __construct(PackageManager $packageManager, YamlFileLoader $yamlFileLoader)
    {
        $this->packageManager = $packageManager;
        $this->yamlFileLoader = $yamlFileLoader;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Imports a configuration for Workspace and Backend User Groups from a yaml file.');
        $this->setHelp('This command imports a configuration from a yaml file. It is used to import the configuration of the workspaces and be_groups tables.');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->packageManager->getActivePackages() as $package) {
            if (!file_exists($package->getPackagePath() . 'Configuration/Workspaces')
                && !file_exists($package->getPackagePath() . 'Configuration/BackendUserGroups')) {
                continue;
            }
            $output->writeln('Importing configuration for package ' . $package->getPackageKey());
            if (file_exists($package->getPackagePath() . 'Configuration/Workspaces')) {
                $this->readAndImportConfiguration($package->getPackagePath() . 'Configuration/Workspaces', $output);
            }
            if (file_exists($package->getPackagePath() . 'Configuration/BackendUserGroups')) {
                $this->readAndImportConfiguration($package->getPackagePath() . 'Configuration/BackendUserGroups', $output);
            }
        }

        return Command::SUCCESS;
    }

    private function readAndImportConfiguration(string $path, OutputInterface $output): void
    {
        $table = $this->determineTable($path);

        $finder = Finder::create()->files()->name('*.yaml')->in($path);
        foreach ($finder as $file) {
            $output->writeln('Importing configuration from file ' . $file);
            $contents = $this->yamlFileLoader->load((string)$file);
            foreach ($contents as $config) {
                $this->importTables($table, (array)$config, $output);
            }
        }
    }

    private function determineTable(string $path): string
    {
        if (str_contains($path, 'Workspaces')) {
            return 'sys_workspace';
        }
        if (str_contains($path, 'BackendUserGroups')) {
            return 'be_groups';
        }
        return '';
    }

    private function importTables(string $table, array $config, OutputInterface $output): void
    {
        $mode = $config['mode'] ?? 'append';
        $conn = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);

        if ($mode === 'replace') {
            $conn->truncate($table);
            $output->writeln('Emptied database table "' . $table . '"');
        }

        foreach ($config['entries'] ?? [] as $entry) {
            if ($mode !== 'update' || !isset($entry['uid'])) {
                $conn->insert($table, $entry);
                $output->writeln('Added ' . json_encode($entry) . ' to database table ' . $table);
                continue;
            }

            $identifiers = ['uid' => $entry['uid']];
            if ($conn->count('uid', $table, $identifiers) === 0) {
                $conn->insert($table, $entry);
                $output->writeln('Added (mode=update, entry does not have uid): ' . json_encode($entry) . ' to database table ' . $table);
                continue;
            }

            $conn->update($table, $entry, $identifiers);
            $output->writeln('Updated (mode=update, entry has uid): ' . json_encode($entry) . ' to database table ' . $table);
        }
    }
}
