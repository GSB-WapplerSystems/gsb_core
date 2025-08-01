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
    /**
     * @param string[]|null[] $envVarsToSetOrUnset
     * @param string[][] $expectedResult
     */
    #[Test]
    #[DataProvider('getEnvVarsToSetAndExpectedResultsForGetVersion')]
    public function getVersionsReturnsSetVersionNumbersWithCorrectFallback(array $envVarsToSetOrUnset, array $expectedResult): void
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
                'GSB_BASE_HELM_CHART_VERSION' => null,
                'GSB_MANDANTEN_HELM_CHART_VERSION' => null,
                'GSB_BASE_CONFIG_VERSION' => null,
            ],
            [
                'versions' => [
                    'gsb' => null,
                    'container' => null,
                    'gsbBaseHelmChart' => null,
                    'gsbMandantenHelmChart' => null,
                    'gsbBaseConfig' => null,
                    'TYPO3' => (new Typo3Version())->getVersion(),
                    'packageCacheHash' => '42',
                ],
            ],
        ];
        yield 'Returns all keys with correct values when environment variables are set' => [
            [
                'GSB_VERSION' => '1.2.3',
                'CONTAINER_VERSION' => '4.5.6',
                'GSB_BASE_HELM_CHART_VERSION' => '7.8.9',
                'GSB_MANDANTEN_HELM_CHART_VERSION' => '10.11.12',
                'GSB_BASE_CONFIG_VERSION' => 'v3.35.0',
            ],
            [
                'versions' => [
                    'gsb' => '1.2.3',
                    'container' => '4.5.6',
                    'gsbBaseHelmChart' => '7.8.9',
                    'gsbMandantenHelmChart' => '10.11.12',
                    'gsbBaseConfig' => 'v3.35.0',
                    'TYPO3' => (new Typo3Version())->getVersion(),
                    'packageCacheHash' => '42',
                ],
            ],
        ];
    }
}
