<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2025 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Patrick Schriner
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\Command;

use DASPRiD\Enum\Exception\IllegalArgumentException;
use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as ExceptionInvalidArgumentException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * List or delete backend users
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BackendUserListCommand extends Command
{
    public const MODE_CSV = 0;
    public const MODE_TABLE = 1;

    protected int $mode = 0;

    /**
     * @var array<int,string>
     */
    protected array $fields = ['uid', 'username', 'realName', 'email', 'deleted', 'disable', 'usergroup', 'admin', 'lastlogin'];

    protected InputInterface $input;

    protected OutputInterface $output;

    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('List backend users by filter criteria')
        ->setHelp(
            <<<'EOF'
The <info>%command.name%</info> lists users:

  <info>%command.full_name%</info>

You can filter by all <comment>fields</comment> in the <comment>be_users</comment> table except for <comment>password</comment>

  <info>%command.full_name% --filter email=xxx@yyy.com</info>

You can filter display the result as a human readable table

  <info>%command.full_name% -f deleted=1 -f admin=1 -m table</info>

EOF
        )
        ->addOption('mode', 'm', InputOption::VALUE_REQUIRED, 'Mode: csv or table')
        ->addOption('filter', 'f', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Filter for the list', []);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        if ((string)$input->getOption('mode') !== '') {
            switch ($input->getOption('mode')) {
                case 'table':
                    $this->mode = static::MODE_TABLE;
                    break;
                case 'csv':
                    $this->mode = static::MODE_CSV;
                    break;
                default:
                    throw new IllegalArgumentException('mode must be csv or table', 1741083906);
            }
        }
    }

    /**
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $filter = $this->sanitizeFilter($input->getOption('filter'));
        $users = $this->getUsers($filter);
        if (count($users) > 0) {
            switch ($this->mode) {
                case static::MODE_CSV:
                    $this->outputCsv($users);
                    break;
                case static::MODE_TABLE:
                    $this->outputTable($users);
                    break;
            }
        }
        return Command::SUCCESS;
    }

    /**
     * @param array<int,array<string,mixed>> $users
     */
    protected function outputCsv(array $users): void
    {
        $memoryBuffer = fopen('php://memory', 'w+');
        if ($memoryBuffer !== false) {
            foreach ($users as $user) {
                fputcsv($memoryBuffer, $user, ';');
            }
            rewind($memoryBuffer);
            $this->output->write((string)stream_get_contents($memoryBuffer));
            fclose($memoryBuffer);
        }
    }

    /**
     * @param array<int,array<string,mixed>> $users
     * @throws ExceptionInvalidArgumentException
     * @throws \DivisionByZeroError
     * @throws \ArithmeticError
     */
    protected function outputTable(array $users): void
    {
        $tableHelper = new Table($this->output);
        $tableHelper->setHeaders($this->fields);
        $tableHelper->addRows($users);
        $tableHelper->render();
    }

    /**
     * @param array<string,string> $filter
     * @return array<int,array<string,mixed>>
     * @throws \UnexpectedValueException
     * @throws Exception
     * @throws \InvalidArgumentException
     */
    protected function getUsers(array $filter): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('be_users');
        $queryBuilder->getRestrictions()->removeAll();
        $where = [];
        foreach ($filter as $field => $value) {
            $where[] = $queryBuilder->expr()->eq($field, $queryBuilder->createNamedParameter($value));
        }
        $users = $queryBuilder
            ->select(...$this->fields)
            ->from('be_users')
            ->where(...$where)
            ->executeQuery()
            ->fetchAllAssociative();
        return $users;
    }

    /**
     * @param array<mixed,mixed> $filter
     *
     * @return array<string,string>
     *
     * @throws \UnexpectedValueException
     * @throws Exception
     * @throws \InvalidArgumentException
     * @throws NoSuchCacheException
     */
    protected function sanitizeFilter(array $filter = []): array
    {
        $connection = $this->connectionPool->getConnectionForTable('be_users');
        $tableDetails = $connection->getSchemaInformation()->introspectTable('be_users');

        $returnFilter = [];
        foreach ($filter as $filterLine) {
            $split = explode('=', $filterLine);
            $field = !empty($split[0]) ? $split[0] : null;
            $value = $split[1] ?? null;
            if ($field !== null && $value !== null && $field !== 'password') {
                if ($tableDetails->hasColumn($field)) {
                    $returnFilter[$field] = $value;
                }
            }
        }
        $connection->close();
        return $returnFilter;
    }

}
