<?php

declare(strict_types=1);

namespace ITZBund\GsbTemplate\Tests\Unit\Command;

use Codeception\Test\Unit;
use ITZBund\GsbTemplate\Command\ConfigurationImportCommand;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Package\PackageManager;

class ConfigurationImportCommandTest extends Unit
{
    /**
     * @throws Exception
     */
    public function testExecute(): void
    {
        $packageManager = $this->createStub(PackageManager::class);
        $yamlFileLoader = $this->createStub(YamlFileLoader::class);

        $command = new ConfigurationImportCommand($packageManager, $yamlFileLoader);

        $package = $this->createStub(\TYPO3\CMS\Core\Package\Package::class);
        $package->method('getPackageKey')->willReturn('gsb_template');
        $package->method('getPackagePath')->willReturn('/var/www/html/vendor/itzbund/gsb-template/');

        $packageManager->method('getActivePackages')->willReturn([$package]);

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Importing configuration for package gsb_template', $output);
        // Add more assertions as needed
    }
}
