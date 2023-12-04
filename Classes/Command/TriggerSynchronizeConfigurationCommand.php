<?php

namespace ITZBund\GsbCore\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TriggerSynchronizeConfigurationCommand extends Command
{
    public function __construct(private readonly ExtensionConfiguration $extensionConfiguration)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->extensionConfiguration->get(GeneralUtility::makeInstance(Random::class)->generateRandomHexString(64));
        } catch (ExtensionConfigurationExtensionNotConfiguredException $exence) {
            return Command::SUCCESS;
        }
        return Command::FAILURE;
    }

}
