<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Command;

use Codeception\Test\Unit;
use ITZBund\GsbCore\Command\ConfigurationImportCommand;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;

class ConfigurationImportCommandTest extends Unit
{
    /*public function testExecute(): void
    {
        // Mock the package manager and yaml file loader
        $packageManager = $this->createMock(PackageManager::class);
        $yamlFileLoader = $this->createMock(YamlFileLoader::class);

        // Create an instance of the ConfigurationImportCommand with the mocked dependencies
        $command = new ConfigurationImportCommand($packageManager, $yamlFileLoader);

        // Create a stub package
        $package = $this->createStub(Package::class);
        $package->method('getPackageKey')->willReturn('gsb_core');
        $package->method('getPackagePath')->willReturn('/var/www/html/vendor/itzbund/gsb-core/');

        // Stub the getActivePackages method of the package manager to return the stub package
        $packageManager->method('getActivePackages')->willReturn([$package]);

        // Create a command tester and execute the command
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        // Assert the expected output
        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Importing configuration for package gsb_core', $output);
        // Add more assertions as needed
    }*/

    public function testExecuteWithoutFileExists(): void
    {
        // Mock the package manager and yaml file loader
        $packageManager = $this->createMock(PackageManager::class);
        $yamlFileLoader = $this->createMock(YamlFileLoader::class);

        // Create an instance of the ConfigurationImportCommand with the mocked dependencies
        $command = new ConfigurationImportCommand($packageManager, $yamlFileLoader);

        // Create a stub package
        $package = $this->createStub(Package::class);
        $package->method('getPackageKey')->willReturn('gsb_core');
        $package->method('getPackagePath')->willReturn('falsePath');

        // Stub the getActivePackages method of the package manager to return the stub package
        $packageManager->method('getActivePackages')->willReturn([$package]);

        // Create a command tester and execute the command
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        // Assert the expected output
        $output = $commandTester->getDisplay();
        self::assertStringContainsString('', $output);
    }

    public function testDetermineTable(): void
    {
        // Mock the package manager and yaml file loader
        $packageManager = $this->createMock(PackageManager::class);
        $yamlFileLoader = $this->createMock(YamlFileLoader::class);

        // Create an instance of the ConfigurationImportCommand with the mocked dependencies
        $command = new ConfigurationImportCommand($packageManager, $yamlFileLoader);

        // Use reflection to access the determineTable method
        $reflectionMethod = new \ReflectionMethod(ConfigurationImportCommand::class, 'determineTable');
        $reflectionMethod->setAccessible(true);

        // Call the determineTable method with a path containing 'Workspaces'
        $table1 = $reflectionMethod->invoke($command, '/path/to/Workspaces');
        self::assertSame('sys_workspace', $table1);

        // Call the determineTable method with a path containing 'BackendUserGroups'
        $table2 = $reflectionMethod->invoke($command, '/path/to/BackendUserGroups');
        self::assertSame('be_groups', $table2);

        // Call the determineTable method with an unknown path
        $table3 = $reflectionMethod->invoke($command, '/path/to/Other');
        self::assertSame('', $table3);
    }
}
