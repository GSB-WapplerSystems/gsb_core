<?php

namespace ITZBund\GsbCore\Tests\Unit\EventListener;

use ITZBund\GsbCore\Configuration\Discovery\ExtendSiteConfigurationLocator;
use ITZBund\GsbCore\Configuration\ExtendSiteConfigurationRegistry;
use ITZBund\GsbCore\EventListener\ExtendsSiteConfigurationEvent;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationLoadedEvent;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ExtendsSiteConfigurationEventTest extends UnitTestCase
{
    /**
     * @var ExtendSiteConfigurationRegistry
     */
    private $registry;

    /**
     * @var ExtendsSiteConfigurationEvent
     */
    private $eventListener;

    protected function setUp(): void
    {
        parent::setUp();

        $package1 = $this->createMock(Package::class);
        $package1->method('getPackagePath')->willReturn('Resources/Fixtures/'); // Set the return value for getPackagePath

        $packageManager = $this->createMock(PackageManager::class);
        $packageManager->method('getActivePackages')->willReturn([$package1]); // Set the return value for getActivePackages

        $locator = new ExtendSiteConfigurationLocator($packageManager);
        $this->registry = new ExtendSiteConfigurationRegistry($locator);
        $this->eventListener = new ExtendsSiteConfigurationEvent($this->registry);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->resetSingletonInstances = true;
    }

    public function testInvokeWithSiteConfigExtends()
    {
        $siteIdentifier = 'gsb_frontend';
        $configFilePath = 'EXT:gsb_core/Resources/Fixtures/Configuration/SiteConfiguration/Extends/gsb/config.yaml';
        $siteConfiguration = ['existing' => 'config'];

        $event = new SiteConfigurationLoadedEvent($siteIdentifier, $siteConfiguration);
        $this->registry->add($siteIdentifier, $configFilePath);

        $mockedLoader = $this->getMockBuilder(YamlFileLoader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockedLoader->expects(self::any())
            ->method('load')
            ->willReturn(['extended' => 'config from ' . $configFilePath]);

        $this->eventListener->__invoke($event);

        // Assert that the configuration has been extended
        $expectedConfiguration = array_merge($siteConfiguration, ['extended' => 'config from ' . $configFilePath]);
        self::assertEquals($expectedConfiguration, $event->getConfiguration());
    }

    public function testInvokeWithoutSiteConfigExtends()
    {
        $siteIdentifier = 'gsb_frontend';
        $siteConfiguration = ['existing' => 'config'];

        $event = new SiteConfigurationLoadedEvent($siteIdentifier, $siteConfiguration);

        $this->eventListener->__invoke($event);

        // Assert that the configuration remains unchanged
        self::assertEquals($siteConfiguration, $event->getConfiguration());
        $this->resetSingletonInstances = true;
    }
}
