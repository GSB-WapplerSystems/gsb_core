<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Utility;

use ITZBund\GsbCore\Utility\EnvironmentVersionsUtility;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class EnvironmentVersionsUtilityTest extends UnitTestCase
{
    protected EnvironmentVersionsUtility $utility;
    /** @var MockObject&Typo3Version */
    protected MockObject $typo3VersionMock;
    /** @var MockObject&PackageManager */
    protected MockObject $packageManagerMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->typo3VersionMock = $this->createMock(Typo3Version::class);
        $this->packageManagerMock = $this->createMock(PackageManager::class);

        $this->utility = new EnvironmentVersionsUtility(
            $this->typo3VersionMock,
            $this->packageManagerMock
        );
    }

    #[Test]
    #[TestDox('EnvironmentVersionsUtility can be instantiated')]
    public function environmentVersionsUtilityCanBeInstantiated(): void
    {
        self::assertInstanceOf(EnvironmentVersionsUtility::class, $this->utility);
    }

    #[Test]
    #[TestDox('GetVersions returns correct structure')]
    public function getVersionsReturnsCorrectStructure(): void
    {
        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertIsArray($result);
        self::assertArrayHasKey('versions', $result);
        self::assertIsArray($result['versions']);
    }

    #[Test]
    #[TestDox('GetVersions includes TYPO3 version')]
    public function getVersionsIncludesTypo3Version(): void
    {
        $typo3Version = '12.4.0';
        $this->typo3VersionMock->method('getVersion')->willReturn($typo3Version);
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertSame($typo3Version, $result['versions']['TYPO3']);
    }

    #[Test]
    #[TestDox('GetVersions includes package cache hash')]
    public function getVersionsIncludesPackageCacheHash(): void
    {
        $cacheHash = 'test-cache-hash';
        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn($cacheHash);

        $result = $this->utility->getVersions();

        self::assertSame($cacheHash, $result['versions']['packageCacheHash']);
    }

    #[Test]
    #[TestDox('GetVersions includes GSB version when environment variable is set')]
    public function getVersionsIncludesGsbVersionWhenEnvironmentVariableIsSet(): void
    {
        $gsbVersion = '1.2.3';
        putenv("GSB_VERSION={$gsbVersion}");

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertSame($gsbVersion, $result['versions']['gsb']);

        putenv('GSB_VERSION');
    }

    #[Test]
    #[TestDox('GetVersions returns null for GSB version when environment variable is not set')]
    public function getVersionsReturnsNullForGsbVersionWhenEnvironmentVariableIsNotSet(): void
    {
        putenv('GSB_VERSION');

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertNull($result['versions']['gsb']);
    }

    #[Test]
    #[TestDox('GetVersions includes container version when environment variable is set')]
    public function getVersionsIncludesContainerVersionWhenEnvironmentVariableIsSet(): void
    {
        $containerVersion = '2.1.0';
        putenv("CONTAINER_VERSION={$containerVersion}");

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertSame($containerVersion, $result['versions']['container']);

        putenv('CONTAINER_VERSION');
    }

    #[Test]
    #[TestDox('GetVersions returns null for container version when environment variable is not set')]
    public function getVersionsReturnsNullForContainerVersionWhenEnvironmentVariableIsNotSet(): void
    {
        putenv('CONTAINER_VERSION');

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertNull($result['versions']['container']);
    }

    #[Test]
    #[TestDox('GetVersions includes GSB base helm chart version when environment variable is set')]
    public function getVersionsIncludesGsbBaseHelmChartVersionWhenEnvironmentVariableIsSet(): void
    {
        $helmChartVersion = '3.0.1';
        putenv("GSB_BASE_HELM_CHART_VERSION={$helmChartVersion}");

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertSame($helmChartVersion, $result['versions']['gsbBaseHelmChart']);

        putenv('GSB_BASE_HELM_CHART_VERSION');
    }

    #[Test]
    #[TestDox('GetVersions returns null for GSB base helm chart version when environment variable is not set')]
    public function getVersionsReturnsNullForGsbBaseHelmChartVersionWhenEnvironmentVariableIsNotSet(): void
    {
        putenv('GSB_BASE_HELM_CHART_VERSION');

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertNull($result['versions']['gsbBaseHelmChart']);
    }

    #[Test]
    #[TestDox('GetVersions includes GSB mandanten helm chart version when environment variable is set')]
    public function getVersionsIncludesGsbMandantenHelmChartVersionWhenEnvironmentVariableIsSet(): void
    {
        $helmChartVersion = '4.2.0';
        putenv("GSB_MANDANTEN_HELM_CHART_VERSION={$helmChartVersion}");

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertSame($helmChartVersion, $result['versions']['gsbMandantenHelmChart']);

        putenv('GSB_MANDANTEN_HELM_CHART_VERSION');
    }

    #[Test]
    #[TestDox('GetVersions returns null for GSB mandanten helm chart version when environment variable is not set')]
    public function getVersionsReturnsNullForGsbMandantenHelmChartVersionWhenEnvironmentVariableIsNotSet(): void
    {
        putenv('GSB_MANDANTEN_HELM_CHART_VERSION');

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertNull($result['versions']['gsbMandantenHelmChart']);
    }

    #[Test]
    #[TestDox('GetVersions includes GSB base config version when environment variable is set')]
    public function getVersionsIncludesGsbBaseConfigVersionWhenEnvironmentVariableIsSet(): void
    {
        $configVersion = '5.1.2';
        putenv("GSB_BASE_CONFIG_VERSION={$configVersion}");

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertSame($configVersion, $result['versions']['gsbBaseConfig']);

        putenv('GSB_BASE_CONFIG_VERSION');
    }

    #[Test]
    #[TestDox('GetVersions returns null for GSB base config version when environment variable is not set')]
    public function getVersionsReturnsNullForGsbBaseConfigVersionWhenEnvironmentVariableIsNotSet(): void
    {
        putenv('GSB_BASE_CONFIG_VERSION');

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertNull($result['versions']['gsbBaseConfig']);
    }

    #[Test]
    #[TestDox('GetVersions handles all environment variables together')]
    public function getVersionsHandlesAllEnvironmentVariablesTogether(): void
    {
        putenv('GSB_VERSION=1.2.3');
        putenv('CONTAINER_VERSION=2.1.0');
        putenv('GSB_BASE_HELM_CHART_VERSION=3.0.1');
        putenv('GSB_MANDANTEN_HELM_CHART_VERSION=4.2.0');
        putenv('GSB_BASE_CONFIG_VERSION=5.1.2');

        $this->typo3VersionMock->method('getVersion')->willReturn('12.4.0');
        $this->packageManagerMock->method('getCacheIdentifier')->willReturn('test-cache-hash');

        $result = $this->utility->getVersions();

        self::assertSame('1.2.3', $result['versions']['gsb']);
        self::assertSame('2.1.0', $result['versions']['container']);
        self::assertSame('3.0.1', $result['versions']['gsbBaseHelmChart']);
        self::assertSame('4.2.0', $result['versions']['gsbMandantenHelmChart']);
        self::assertSame('5.1.2', $result['versions']['gsbBaseConfig']);
        self::assertSame('12.4.0', $result['versions']['TYPO3']);
        self::assertSame('test-cache-hash', $result['versions']['packageCacheHash']);

        // Clean up environment variables
        putenv('GSB_VERSION');
        putenv('CONTAINER_VERSION');
        putenv('GSB_BASE_HELM_CHART_VERSION');
        putenv('GSB_MANDANTEN_HELM_CHART_VERSION');
        putenv('GSB_BASE_CONFIG_VERSION');
    }

    protected function tearDown(): void
    {
        // Clean up any environment variables that might have been set
        putenv('GSB_VERSION');
        putenv('CONTAINER_VERSION');
        putenv('GSB_BASE_HELM_CHART_VERSION');
        putenv('GSB_MANDANTEN_HELM_CHART_VERSION');
        putenv('GSB_BASE_CONFIG_VERSION');

        parent::tearDown();
    }
}
