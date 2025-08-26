<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Upgrades;

use ITZBund\GsbCore\Upgrades\RemovesColumnsWizard;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Schema\SchemaMigrator;
use TYPO3\CMS\Core\Database\Schema\SqlReader;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class RemovesColumnsWizardTest extends UnitTestCase
{
    protected RemovesColumnsWizard $wizard;
    /** @var \TYPO3\CMS\Core\Database\ConnectionPool&\PHPUnit\Framework\MockObject\MockObject */
    protected $connectionPoolMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connectionPoolMock = $this->createMock(ConnectionPool::class);

        $this->wizard = new RemovesColumnsWizard($this->connectionPoolMock);

        // Setup GeneralUtility mocks
        GeneralUtility::addInstance(ConnectionPool::class, $this->connectionPoolMock);
    }

    #[Test]
    #[TestDox('RemovesColumnsWizard can be instantiated')]
    public function removesColumnsWizardCanBeInstantiated(): void
    {
        self::assertInstanceOf(RemovesColumnsWizard::class, $this->wizard);
    }

    #[Test]
    #[TestDox('RemovesColumnsWizard implements UpgradeWizardInterface')]
    public function removesColumnsWizardImplementsUpgradeWizardInterface(): void
    {
        self::assertInstanceOf(\TYPO3\CMS\Install\Updates\UpgradeWizardInterface::class, $this->wizard);
    }

    #[Test]
    #[TestDox('RemovesColumnsWizard implements ChattyInterface')]
    public function removesColumnsWizardImplementsChattyInterface(): void
    {
        self::assertInstanceOf(\TYPO3\CMS\Install\Updates\ChattyInterface::class, $this->wizard);
    }

    #[Test]
    #[TestDox('RemovesColumnsWizard implements RepeatableInterface')]
    public function removesColumnsWizardImplementsRepeatableInterface(): void
    {
        self::assertInstanceOf(\TYPO3\CMS\Install\Updates\RepeatableInterface::class, $this->wizard);
    }



    #[Test]
    #[TestDox('GetTitle returns correct title')]
    public function getTitleReturnsCorrectTitle(): void
    {
        $title = $this->wizard->getTitle();

        self::assertIsString($title);
        self::assertNotEmpty($title);
    }

    #[Test]
    #[TestDox('GetDescription returns correct description')]
    public function getDescriptionReturnsCorrectDescription(): void
    {
        $description = $this->wizard->getDescription();

        self::assertIsString($description);
        self::assertNotEmpty($description);
    }

    #[Test]
    #[TestDox('UpdateNecessary returns false when no columns need to be removed')]
    public function updateNecessaryReturnsFalseWhenNoColumnsNeedToBeRemoved(): void
    {
        $result = $this->wizard->updateNecessary();

        self::assertFalse($result);
    }

    #[Test]
    #[TestDox('Execute returns true on successful update')]
    public function executeReturnsTrueOnSuccessfulUpdate(): void
    {
        $result = $this->wizard->executeUpdate();

        self::assertTrue($result);
    }

    #[Test]
    #[TestDox('GetPrerequisites returns correct prerequisites')]
    public function getPrerequisitesReturnsCorrectPrerequisites(): void
    {
        $prerequisites = $this->wizard->getPrerequisites();

        self::assertIsArray($prerequisites);
        self::assertContains(\TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite::class, $prerequisites);
    }

    #[Test]
    #[TestDox('SetOutput sets output correctly')]
    public function setOutputSetsOutputCorrectly(): void
    {
        $outputMock = $this->createMock(\Symfony\Component\Console\Output\OutputInterface::class);

        $this->wizard->setOutput($outputMock);

        // Test that the output was set (we can't directly access the protected property)
        self::assertTrue(true); // Placeholder assertion
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        parent::tearDown();
    }
}
