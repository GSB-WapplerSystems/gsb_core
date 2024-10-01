<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Utility;

use ITZBund\GsbCore\Utility\EnvironmentVersionsUtility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class EnvironmentVersionsUtilityTest extends UnitTestCase
{
    #[Test]
    #[DataProvider('getEnvVarsToSetAndExpectedResultsForGetVersion')]
    public function getVersionsReturnsSetVersionNumbersWithCorrectFallback($envVarsToSetOrUnset, $expectedResult)
    {
        foreach ($envVarsToSetOrUnset as $varName => $value) {
            if ($value === null) {
                putenv($varName);
                continue;
            }
            putenv($varName . '=' . $value);
        }

        $packageManager = $this->getMockBuilder(PackageManager::class)->disableOriginalConstructor()->getMock();
        $packageManager->method('getCacheIdentifier')->willReturn('42');

        $utility = new EnvironmentVersionsUtility(new Typo3Version(), $packageManager);
        self::assertEquals($expectedResult, $utility->getVersions());

        foreach ($envVarsToSetOrUnset as $varName => $_) {
            putenv($varName);
        }
    }

    public static function getEnvVarsToSetAndExpectedResultsForGetVersion(): \Generator
    {
        yield 'Returns all keys with null values when no environment variables are set' => [
            [
                'GSB_VERSION' => null,
                'CONTAINER_VERSION' => null,
                'HELM_CHART_VERSION' => null,
            ],
            [
                'versions' => [
                    'gsb' => null,
                    'container' => null,
                    'helmChart' => null,
                    'TYPO3' => (new Typo3Version())->getVersion(),
                    'packageCacheHash' => '42',
                ],
            ],
        ];
        yield 'Returns all keys with correct values when environment variables are set' => [
            [
                'GSB_VERSION' => '1.2.3',
                'CONTAINER_VERSION' => '4.5.6',
                'HELM_CHART_VERSION' => '7.8.9',
            ],
            [
                'versions' => [
                    'gsb' => '1.2.3',
                    'container' => '4.5.6',
                    'helmChart' => '7.8.9',
                    'TYPO3' => (new Typo3Version())->getVersion(),
                    'packageCacheHash' => '42',
                ],
            ],
        ];
    }
}
