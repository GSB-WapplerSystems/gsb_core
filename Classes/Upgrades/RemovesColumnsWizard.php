<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Marco Luig, Patrick Schriner
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
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * More accurate database:updateschema --destructive
 */
#[UpgradeWizard('gsbcore_removeColumnsWizard')]
class RemovesColumnsWizard implements UpgradeWizardInterface, ChattyInterface, RepeatableInterface
{
    protected const TABLES_AND_COLUMNS = [
        'sys_file_reference' => [
            'is_accessible',
        ],
    ];

    /**
     * @var string Prefix of deleted tables
     */
    protected $deletedPrefix = 'zzz_deleted_';

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(
        private readonly Features $features,
    ) {}

    /**
     * Returns the title of the upgrade wizard.
     */
    public function getTitle(): string
    {
        return 'Remove specific columns from database';
    }

    /**
     * Returns the description of the upgrade wizard.
     */
    public function getDescription(): string
    {
        return 'Checks if specific columns are not definied in the TCA and can be removed from the database.';
    }

    /**
     * Executes the update process.
     */
    public function executeUpdate(): bool
    {
        $filteredTables = $this->filterByUpdateNeccessary(self::TABLES_AND_COLUMNS);
        foreach ($filteredTables as $table => $columns) {
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
            foreach ($columns as $column) {
                $connection->executeStatement('ALTER TABLE ' . $table . ' DROP COLUMN ' . $column);
            }
        }

        return true;
    }

    /**
     * Determines if the update is necessary.
     */
    public function updateNecessary(): bool
    {
        return count($this->filterByUpdateNeccessary(self::TABLES_AND_COLUMNS)) > 0;
    }

    /**
     * @param array<string, array<int, string>> $tables
     * @return array<string, array<int, string>>
     */
    private function filterByUpdateNeccessary(array $tables): array
    {
        $filteredTables = [];
        foreach ($tables as $table => $columns) {
            foreach ($columns as $column) {
                if ($this->columnShouldBeRemoved($table, $column)) {
                    $filteredTables[$table] = $filteredTables[$table] ?? [];
                    $filteredTables[$table][] = $column;
                }
            }
        }
        return $filteredTables;
    }

    private function columnShouldBeRemoved(string $tableName, string $columnName): bool
    {
        return !$this->columnDefinedInTca($tableName, $columnName) && $this->columnExistsInDatabase($tableName, $columnName);
    }

    /**
     * Helper method to check if a column exists in a table.
     */
    private function columnExistsInDatabase(string $tableName, string $columnName): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($tableName);
        $schemaManager = $connection->createSchemaManager();
        $columns = $schemaManager->listTableColumns($tableName);

        return array_key_exists($columnName, $columns);
    }

    /**
     * Helper method to check if a column is defined in the TCA.
     */
    private function columnDefinedInTca(string $tableName, string $columnName): bool
    {
        $tca = $GLOBALS['TCA'][$tableName] ?? null;

        if (is_array($tca) && isset($tca['columns'][$columnName])) {
            return true;
        }

        return false;
    }

    /**
     * Returns an array of identifiers for prerequisite wizards.
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }
}
