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

use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as ExceptionInvalidArgumentException;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * List or delete backend users
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BackendUserDeleteCommand extends Command
{
    /**
     * @var array<int,string>
     */
    protected array $fields =  ['uid', 'username', 'email', 'realName', 'deleted'];

    protected bool $dry = false;

    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Deletes users by criteria')
        ->setHelp(
            <<<'EOF'
The <info>%command.name%</info> deletes users:

  <info>%command.full_name%</info>

You can dry run the command by using the <comment>--dry</comment> option:

  <info>%command.full_name% --uid=42 --dry</info>

Note: Using at least one of the <comment>--uid</comment>, <comment>--email</comment> or <comment>--username</comment> options is required.

EOF
        )
        ->addOption('email', 'e', InputOption::VALUE_REQUIRED, 'email to use as criteria')
        ->addOption('uid', null, InputOption::VALUE_REQUIRED, 'uid to use as criteria')
        ->addOption('username', 'name', InputOption::VALUE_REQUIRED, 'username to use as criteria')
        ->addOption('dry', null, InputOption::VALUE_NONE, 'Dry run');
    }

    /**
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->dry = $input->getOption('dry') ?? false;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->getUsers($input);
        if ($this->dry) {
            $formatter = new FormatterHelper();
            $formattedBlock = $formatter->formatBlock('Dry run', 'info', true);
            $output->writeln($formattedBlock);
        }
        if ($output->isVerbose() && count($users) == 0) {
            $output->writeln('<warning>No matching users</warning>');
        }
        if ($output->isVerbose() || $this->dry) {
            $table = new Table($output);
            $table->setHeaders($this->fields);
            $table->addRows($users);
            $table->render();
        }
        if ($this->dry) {
            return Command::SUCCESS;
        }
        $this->deleteUsers($users);
        return Command::SUCCESS;
    }

    /**
     * @param array<int,array<string,mixed>> $users
     */
    protected function deleteUsers(array $users): void
    {
        Bootstrap::initializeBackendAuthentication();
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->admin = true;
        $cmd = [];
        foreach ($users as $user) {
            if ((int)$user['deleted'] == 1) {
                $this->forceDelete($user['uid']);
                continue;
            }

            $cmd['be_users'][$user['uid']]['delete'] = 1;
        }
        $dataHandler->start([], $cmd);
        $dataHandler->process_cmdmap();
    }

    protected function forceDelete(int $uid): void
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('be_users');
        $queryBuilder->getRestrictions()->removeAll();
        $queryBuilder->delete('be_users')
            ->where($queryBuilder->expr()->eq('uid', $uid))
            ->executeStatement();
    }

    /**
     * @param InputInterface $input
     * @return array<int,array<string,mixed>>
     * @throws \UnexpectedValueException
     * @throws Exception
     * @throws \InvalidArgumentException
     * @throws ExceptionInvalidArgumentException
     */
    protected function getUsers(InputInterface $input): array
    {

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('be_users');
        $queryBuilder->getRestrictions()->removeAll();
        $where = [];
        foreach (['email', 'uid', 'username'] as $field) {
            $optionValue = $input->getOption($field) ?? false;
            if ($optionValue !== false) {
                $where[] = $queryBuilder->expr()->eq($field, $queryBuilder->createNamedParameter($optionValue));
            }
        }
        // we require a filter
        if (count($where) == 0) {
            return [];
        }
        $users = $queryBuilder
            ->select(...$this->fields)
            ->from('be_users')
            ->where(...$where)
            ->executeQuery()
            ->fetchAllAssociative();
        return $users;
    }

}
