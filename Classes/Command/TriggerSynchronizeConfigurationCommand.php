<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

/**
 * Used to trigger ExtensionConfiguration->synchronizeExtConfTemplateWithLocalConfigurationOfAllExtensions
 */
class TriggerSynchronizeConfigurationCommand extends Command
{
    public function __construct(private readonly ExtensionConfiguration $extConfiguration)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->extConfiguration->synchronizeExtConfTemplateWithLocalConfigurationOfAllExtensions(true);
        return Command::SUCCESS;
    }
}
