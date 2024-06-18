<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Configuration;

use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use ITZBund\GsbCore\Configuration\PackageHelper;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Package\PackageInterface;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;

class PackageHelperTest extends Unit
{
    protected function createMockPackage(string $packageKey, $composerExtra = null): PackageInterface
    {
        $package = $this->createMock(PackageInterface::class);
        $package->method('getPackageKey')->willReturn($packageKey);
        if ($composerExtra) {
            $package->method('getValueFromComposerManifest')->with('extra')->willReturn($composerExtra);
        }
        return $package;
    }

    protected function createMockPackageManager(array $packages): PackageManager
    {
        $packageManager = $this->createMock(PackageManager::class);
        $packageManager->method('getActivePackages')->willReturn($packages);
        return $packageManager;
    }

    protected function createMockSite(array $configuration): Site
    {
        $site = $this->createMock(Site::class);
        $site->method('getConfiguration')->willReturn($configuration);
        return $site;
    }

    public function testGetSitePackageFromSiteWithUnknownPackageKey(): void
    {
        $packageKey = 'unknown_package';
        $packageManager = $this->createMockPackageManager([]);

        $site = $this->createMockSite(['sitePackage' => $packageKey]);

        $packageHelper = new PackageHelper($packageManager, $this->createMock(SiteFinder::class));
        $result = $packageHelper->getSitePackageFromSite($site);

        self::assertNull($result);
        $site = $this->createMockSite(['' => $packageKey]);
        $site->method('getConfiguration')->willReturn([]);
        $result = $packageHelper->getSitePackageFromSite($site);
        self::assertNull($result);

        $packageKey = 'unknown_package';
        $packageManager = $this->createMock(PackageManager::class);
        $site = $this->createMockSite(['sitePackage' => $packageKey]);
        $site->method('getConfiguration')->willReturn([$site]);
        $packageHelper = new PackageHelper($packageManager, $this->createMock(SiteFinder::class));
        $result = $packageHelper->getSitePackageFromSite($site);

        self::assertNull($result);
    }

    public function testGetSiteListForSiteModule(): void
    {
        $packageKey1 = 'site_extension1';
        $packageKey2 = 'site_extension2';
        $packageKey3 = 'gsb_core';
        $currentValue = 'site_extension2';

        $package1 = $this->createMockPackage($packageKey1);
        $package2 = $this->createMockPackage($packageKey2);
        $package3 = $this->createMockPackage($packageKey3);

        $packageManager = $this->createMockPackageManager([$package1, $package2, $package3]);

        $fieldDefinition = [
            'items' => [],
            'row' => ['sitePackage' => $currentValue],
        ];

        $packageHelper = new PackageHelper($packageManager, $this->createMock(SiteFinder::class));
        $packageHelper->getSiteListForSiteModule($fieldDefinition);

        $expectedItems = [
            ['-- None --', ''],
            [$packageKey1, $packageKey1],
            [$packageKey2, $packageKey2],
            [$packageKey3, $packageKey3],
        ];

        self::assertSame($expectedItems, $fieldDefinition['items']);

        $fieldDefinition = [
            'items' => [['foo', 'foo'], ['bar', 'bar']],
            'row' => ['sitePackage' => $currentValue],
        ];

        $packageHelper = new PackageHelper($packageManager, $this->createMock(SiteFinder::class));
        $packageHelper->getSiteListForSiteModule($fieldDefinition);

        $expectedItems = [
            ['foo', 'foo'],
            ['bar', 'bar'],
            ['-- None --', ''],
            [$packageKey1, $packageKey1],
            [$packageKey2, $packageKey2],
            [$packageKey3, $packageKey3],
        ];

        self::assertSame($expectedItems, $fieldDefinition['items']);
    }

    protected function getPackagesForIsSitepackage(): array
    {
        $composerExtra = new \stdClass();
        $composerExtra->{'itzbund/gsb-core'} = new \stdClass();
        $composerExtra->{'itzbund/gsb-core'}->isSitePackage = true;
        return [
            'EXT:extension_site' => ['extension_site', true],
            'EXT:site_extension' =>  ['site_extension', true],
            'EXT:gsb_core ' => ['gsb_core', 'true'],
            'EXT:site_extension1_impexp' => ['site_extension1_impexp', false],
            'EXT:with_composer_extra' => ['with_composer_extra', true, $composerExtra],
        ];
    }

    #[Test]
    #[DataProvider('getPackagesForIsSitepackage')]
    public function isSitepackagesWorks($packageKey, $expected, $composerExtraSet = null): void
    {
        $package = $this->createMockPackage($packageKey, $composerExtraSet);
        $packageHelper = new PackageHelper($this->createMock(PackageManager::class), $this->createMock(SiteFinder::class));
        self::assertEquals($expected, $packageHelper->isSitePackage($package));
    }
}
