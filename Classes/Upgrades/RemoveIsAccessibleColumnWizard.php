<?php
declare(strict_types=1);

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
