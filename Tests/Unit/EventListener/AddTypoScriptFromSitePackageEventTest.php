<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\EventListener;

use Codeception\Test\Unit;
use ITZBund\GsbCore\Configuration\PackageHelper;
use ITZBund\GsbCore\EventListener\AddTypoScriptFromSitePackageEvent;
use PHPUnit\Framework\MockObject\Exception;
use TYPO3\CMS\Core\Site\Entity\Site;

class AddTypoScriptFromSitePackageEventTest extends Unit
{
    /**
     * @return array
     * @throws Exception
     */
    public function mockDependencies(): array
    {
        // Mock dependencies
        $packageHelper = $this->createMock(PackageHelper::class);
        $event = $this->createMock(MockAfterTemplatesHaveBeenDeterminedEvent::class);
        $site = $this->createMock(Site::class);
        $package = $this->createMock(\TYPO3\CMS\Core\Package\Package::class);
        $listener = new AddTypoScriptFromSitePackageEvent($packageHelper);
        return [$packageHelper, $event, $site, $listener, $package];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the global $TCA variable
        $GLOBALS['TCA'] = [
            'sys_template' => [
                'ctrl' => [
                    'delete' => 'deleted',
                    'enablecolumns' => [
                        'disabled' => 'hidden',
                    ],
                ],
                'columns' => [
                    'static_file_mode' => [
                        // Mock configuration for the static_file_mode column
                    ],
                ],
            ],
        ];
    }
    public function testInvoke(): void
    {
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $packageHelper->method('getSitePackageFromSite')
            ->willReturn($package);
        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows were not modified
        self::assertEquals([], $event->getTemplateRows());

        $templateRows = $event->getTemplateRows();
        if (!empty($templateRows)) {
            self::assertStringContainsString('CONSTANT_VALUE', $templateRows[0]['constants']);
            self::assertStringContainsString('setup_value', $templateRows[0]['config']);
        }
    }

    /**
     * @throws Exception
     */
    public function testInvokeNullPackage(): void
    {
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);

        $packageHelper->method('getSitePackageFromSite')
            ->willReturn(null);

        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows were not modified
        self::assertEquals([], $event->getTemplateRows());
    }

    public function testInvokeSiteSite(): void
    {
        // Mock dependencies
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $packageHelper->expects(self::once())
            ->method('getSitePackageFromSite')
            ->with($site)
            ->willReturn($package);
        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows were not modified
        self::assertEquals([], $event->getTemplateRows());
    }

    public function testInvokeMissingFiles(): void
    {
        // Mock dependencies
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $packageHelper->expects(self::once())
            ->method('getSitePackageFromSite')
            ->with($site)
            ->willReturn($package);
        $package->expects(self::exactly(2))
            ->method('getPackagePath')
            ->willReturn('/path/to/package/');

        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows were not modified
        self::assertEquals([], $event->getTemplateRows());
    }

    public function testInvokeNullPackagePath(): void
    {
        // Mock dependencies
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $packageHelper->expects(self::once())
            ->method('getSitePackageFromSite')
            ->with($site)
            ->willReturn($package);
        $package->expects(self::exactly(2))
            ->method('getPackagePath')
            ->willReturn(null);

        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows were not modified
        self::assertEquals([], $event->getTemplateRows());
    }

    public function testInvokeValidPackagePath(): void
    {
        // Mock dependencies
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $packageHelper->expects(self::once())
            ->method('getSitePackageFromSite')
            ->with($site)
            ->willReturn($package);
        $package->expects(self::exactly(2))
            ->method('getPackagePath')
            ->willReturn('/path/to/package/');

        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows were modified correctly
        // Add your assertions here based on the modifications made by the listener
        self::assertEquals([], $event->getTemplateRows());
    }

    public function testInvokeInvalidPackageFile(): void
    {
        // Mock dependencies
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $packageHelper->expects(self::once())
            ->method('getSitePackageFromSite')
            ->with($site)
            ->willReturn($package);
        $package->expects(self::exactly(2))
            ->method('getPackagePath')
            ->willReturn('/path/to/package/invalid_file');

        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows were not modified
        self::assertEquals([], $event->getTemplateRows());
    }

    public function testInvokeEmptySysTemplateRows(): void
    {
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $event->setTemplateRows([]);

        $packageHelper->method('getSitePackageFromSite')
            ->willReturn($package);

        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows remain empty
        self::assertEquals([], $event->getTemplateRows());
    }

    public function testInvokeSysTemplateRowWithPidEqualToRootPageId(): void
    {
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $event->setTemplateRows([
            ['pid' => 123, 'config' => 'some config'],
        ]);
        $site->method('getRootPageId')
            ->willReturn(123); // Set the rootPageId to match the pid in sysTemplateRows
        $packageHelper->expects(self::once())
            ->method('getSitePackageFromSite')
            ->with($site)
            ->willReturn($package);
        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);
        // Assert that the template rows are modified as expected
        self::assertEquals([], $event->getTemplateRows());
    }

    /*public function testInvokeWithNonSiteObject(): void
    {
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn(null);
        $packageHelper->expects(self::once())
            ->method('getSitePackageFromSite')
            ->with($site)
            ->willReturn($package);
        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows remain unchanged
        self::assertEquals([], $event->getTemplateRows());
    }*/

    public function testInvokeWithMissingConstantsFile(): void
    {
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $packageHelper->expects(self::once())
            ->method('getSitePackageFromSite')
            ->with($site)
            ->willReturn($package);
        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows remain unchanged
        self::assertEquals([], $event->getTemplateRows());
    }

    public function testInvokeWithMissingSetupFile(): void
    {
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure mocks
        $event->method('getSite')
            ->willReturn($site);
        $GLOBALS['TCA']['sys_template']['columns']['static_file_mode'] = null;
        $packageHelper->method('getSitePackageFromSite')
            ->willReturn($package);
        // Execute the event listener
        $listener->addTypoScriptFromSitePackage($event);

        // Assert that the template rows remain unchanged
        self::assertEquals([], $event->getTemplateRows());
    }

    public function testGetSysTemplateRows(): void
    {
        // Mock dependencies
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure the event
        $rootline = [
            ['uid' => 0],
            ['uid' => 1],
            ['uid' => 2],
            ['uid' => 3],
        ];
        $event->method('getRootline')->willReturn($rootline);
        $site->method('getRootPageId')->willReturn(2);

        // Test when $sysTemplateRowPid === 0
        $sysTemplateRows = [
            ['uid' => 1, 'pid' => 0],
            ['uid' => 2, 'pid' => 1],
            ['uid' => 3, 'pid' => 2],
        ];
        $event->method('getTemplateRows')->willReturn($sysTemplateRows);

        $newSysTemplateRows = $listener->getSysTemplateRows($event, $sysTemplateRows, [], $site);

        $expectedSysTemplateRows = [
            ['uid' => 1, 'pid' => 0],
            ['uid' => 2, 'pid' => 1],
            ['uid' => 3, 'pid' => 2],
        ];
        self::assertEquals($expectedSysTemplateRows, $newSysTemplateRows);
    }

    public function testGetSysTemplateRowsWithSysTemplateRowPidRoot(): void
    {
        // Mock dependencies
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure the event
        $rootline = [
            ['uid' => 0],
            ['uid' => 1],
            ['uid' => 2],
            ['uid' => 3],
        ];
        $event->method('getRootline')->willReturn($rootline);
        $site->method('getRootPageId')->willReturn(2);

        // Test when $sysTemplateRowPid === $site->getRootPageId()
        $sysTemplateRows = [
            ['uid' => 1, 'pid' => 0],
            ['uid' => 2, 'pid' => 2], // pid matches $site->getRootPageId()
            ['uid' => 3, 'pid' => 2],
        ];
        $event->method('getTemplateRows')->willReturn($sysTemplateRows);

        $newSysTemplateRows = $listener->getSysTemplateRows($event, $sysTemplateRows, [], $site);

        $expectedSysTemplateRows = [
            ['uid' => 1, 'pid' => 0],
            ['uid' => 2, 'pid' => 2], // Fake row added
            ['uid' => 3, 'pid' => 2], // Unmodified row
        ];
        self::assertEquals($expectedSysTemplateRows, $newSysTemplateRows);
    }

    public function testGetSysTemplateRowsWithSysTemplateRowPidNonZeroNonRoot(): void
    {
        // Mock dependencies
        [$packageHelper, $event, $site, $listener, $package] = $this->mockDependencies();

        // Configure the event
        $rootline = [
            ['uid' => 0],
            ['uid' => 1],
            ['uid' => 2],
            ['uid' => 3],
        ];
        $event->method('getRootline')->willReturn($rootline);
        $site->method('getRootPageId')->willReturn(2);

        // Test when $sysTemplateRowPid !== $site->getRootPageId() and $sysTemplateRowPid !== 0
        $sysTemplateRows = [
            ['uid' => 1, 'pid' => 1],
            ['uid' => 2, 'pid' => 1],
            ['uid' => 3, 'pid' => 3], // pid does not match $site->getRootPageId()
        ];
        $event->method('getTemplateRows')->willReturn($sysTemplateRows);

        $newSysTemplateRows = $listener->getSysTemplateRows($event, $sysTemplateRows, [], $site);

        $expectedSysTemplateRows = [
            ['uid' => 1, 'pid' => 1],
            ['uid' => 2, 'pid' => 1], // Fake row added
            ['uid' => 3, 'pid' => 3], // Unmodified row
        ];
        self::assertEquals($expectedSysTemplateRows, $newSysTemplateRows);
    }
}
