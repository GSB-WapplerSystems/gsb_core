<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Marco Luig
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\Upgrades;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class RemoveIsAccessibleColumnWizard implements UpgradeWizardInterface
{
    /**
     * Returns the unique identifier for the upgrade wizard.
     */
    public function getIdentifier(): string
    {
        return 'removeIsAccessibleColumnFromSysFileReference';
    }

    /**
     * Returns the title of the upgrade wizard.
     */
    public function getTitle(): string
    {
        return 'Remove is_accessible column from sys_file_reference';
    }

    /**
     * Returns the description of the upgrade wizard.
     */
    public function getDescription(): string
    {
        return 'Checks if the is_accessible column exists in the sys_file_reference table and removes it.';
    }

    /**
     * Checks if the upgrade wizard is required.
     */
    public function executeUpdate(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_file_reference');

        if ($this->doesColumnExist($connection, 'sys_file_reference', 'is_accessible')) {
            // Remove the column if it exists
            $connection->executeStatement('ALTER TABLE sys_file_reference DROP COLUMN is_accessible');
        }

        return true;
    }

    /**
     * Determines if the update is necessary.
     */
    public function updateNecessary(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_file_reference');

        return $this->doesColumnExist($connection, 'sys_file_reference', 'is_accessible');
    }

    /**
     * Helper method to check if a column exists in a table.
     */
    private function doesColumnExist(\Doctrine\DBAL\Connection $connection, string $tableName, string $columnName): bool
    {
        $schemaManager = $connection->createSchemaManager();
        $columns = $schemaManager->listTableColumns($tableName);

        return array_key_exists($columnName, $columns);
    }

    /**
     * Returns an array of identifiers for prerequisite wizards.
     */
    public function getPrerequisites(): array
    {
        // No prerequisites for this wizard
        return [];
    }
}
