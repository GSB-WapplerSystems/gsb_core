<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Command;

use ITZBund\GsbCore\Command\BackendUserDeleteCommand;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class BackendUserDeleteCommandTest extends UnitTestCase
{
    protected BackendUserDeleteCommand $command;
    /** @var MockObject&ConnectionPool */
    protected MockObject $connectionPoolMock;
    /** @var MockObject&Connection */
    protected MockObject $connectionMock;
    /** @var MockObject&QueryBuilder */
    protected MockObject $queryBuilderMock;
    /** @var MockObject&ExpressionBuilder */
    protected MockObject $expressionBuilderMock;
    /** @var MockObject&DataHandler */
    protected MockObject $dataHandlerMock;
    /** @var MockObject&\TYPO3\CMS\Core\Database\Query\Restriction\QueryRestrictionContainerInterface */
    protected MockObject $restrictionContainerMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connectionPoolMock = $this->createMock(ConnectionPool::class);
        $this->connectionMock = $this->createMock(Connection::class);
        $this->queryBuilderMock = $this->createMock(QueryBuilder::class);
        $this->expressionBuilderMock = $this->createMock(ExpressionBuilder::class);
        $this->dataHandlerMock = $this->createMock(DataHandler::class);
        $this->restrictionContainerMock = $this->createMock(\TYPO3\CMS\Core\Database\Query\Restriction\QueryRestrictionContainerInterface::class);

        $this->command = new BackendUserDeleteCommand($this->connectionPoolMock);

        // Setup GeneralUtility mock
        GeneralUtility::addInstance(DataHandler::class, $this->dataHandlerMock);
    }

    #[Test]
    #[TestDox('BackendUserDeleteCommand can be instantiated')]
    public function backendUserDeleteCommandCanBeInstantiated(): void
    {
        self::assertInstanceOf(BackendUserDeleteCommand::class, $this->command);
    }

    #[Test]
    #[TestDox('Command has correct name')]
    public function commandHasCorrectName(): void
    {
        self::assertSame('gsb:backend-user:delete', $this->command->getName());
    }

    #[Test]
    #[TestDox('Command has correct description')]
    public function commandHasCorrectDescription(): void
    {
        self::assertSame('Deletes users by criteria', $this->command->getDescription());
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        parent::tearDown();
    }
}
