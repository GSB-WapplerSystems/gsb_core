<?php

namespace ITZBund\GsbCore\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Used to trigger ExtensionConfiguration->synchronizeExtConfTemplateWithLocalConfigurationOfAllExtensions
 */
class TriggerSynchronizeConfigurationCommand extends Command
{
    public function __construct(private readonly ExtensionConfiguration $extensionConfiguration)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->extensionConfiguration->synchronizeExtConfTemplateWithLocalConfigurationOfAllExtensions(true);
        return Command::SUCCESS;
    }

}
