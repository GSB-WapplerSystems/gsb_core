<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace ITZBund\GsbTemplate\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ConfigurationImportCommandTest extends UnitTestCase
{
    public function tearDown(): void
    {
        restore_error_handler();
    }
    /**
     * @test
     */
    public function executeWillFlushTheQueue(): void
    {
        $packageManager = \Codeception\Stub::make(PackageManager::class, [
            'getPackagePath'=>'vendor/gsb_template',
        ]);
        $yamlFileLoader = \Codeception\Stub::make(YamlFileLoader::class, []);

        $command = $this->getMockBuilder(ConfigurationImportCommand::class)
            ->addMethods(['onlyMethods'])
            ->setConstructorArgs([$packageManager, $yamlFileLoader])
            ->getMock();

        $tester = new CommandTester($command);
        $tester->execute([], []);

        self::assertEquals(Command::SUCCESS, $tester->execute([], []));
    }
}
