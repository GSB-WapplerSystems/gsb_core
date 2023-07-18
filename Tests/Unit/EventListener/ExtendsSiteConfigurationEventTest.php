<?php

namespace ITZBund\GsbCore\Tests\Unit\EventListener;

use ITZBund\GsbCore\Configuration\Discovery\ExtendSiteConfigurationLocator;
use ITZBund\GsbCore\Configuration\ExtendSiteConfigurationRegistry;
use ITZBund\GsbCore\EventListener\ExtendsSiteConfigurationEvent;
use Mockery as m;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationLoadedEvent;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
        $packageManager = $this->createMock(PackageManager::class);
        $package1 = $this->createMock(Package::class);
        $package1->method('getPackagePath')->willReturn('Resources/Fixtures/');
        $packageManager->method('getActivePackages')->willReturn([$package1]);

        $objectManager = GeneralUtility::makeInstance(GeneralUtility::class);
        $objectManager->setSingletonInstance(PackageManager::class, $packageManager);
        //$objectManager->makeInstance(PackageManager::class, $packageManager);

        // Erstelle den Locator und das Registry-Objekt
        $locator = new ExtendSiteConfigurationLocator($packageManager);
        $this->registry = new ExtendSiteConfigurationRegistry($locator);
        $this->eventListener = new ExtendsSiteConfigurationEvent($this->registry);
    }

    protected function tearDown(): void
    {
        $this->resetSingletonInstances = true;
        parent::tearDown();
    }

    /*public function testInvokeWithSiteConfigExtends()
    {
        $package1 = $this->createMock(Package::class);
        $package1->method('getPackagePath')->willReturn('Resources/Fixtures/'); // Set the return value for getPackagePath

        $packageManager = $this->createMock(PackageManager::class);
        $packageManager->method('getActivePackages')->willReturn([$package1]); // Set the return value for getActivePackages

        $siteIdentifier = 'gsb_frontend';
        $configFilePath = 'EXT:gsb_core/Resources/Fixtures/Configuration/SiteConfiguration/Extends/gsb/config.yaml';
        $siteConfiguration = ['existing' => 'config'];

        //$event = new SiteConfigurationLoadedEvent($siteIdentifier, $siteConfiguration);
        $this->registry->add($siteIdentifier, $configFilePath);
        $event = m::mock('overload:' . SiteConfigurationLoadedEvent::class);

        $event->shouldReceive('getConfiguration')
            ->andReturn($siteConfiguration);
        $event->shouldReceive('getSiteIdentifier')
        ->andReturn($siteIdentifier);

        $result = $event->getConfiguration();

        self::assertEquals($siteConfiguration, $result);

        $mockedLoader = $this->getMockBuilder(YamlFileLoader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockedLoader->expects(self::any())
            ->method('load')
            ->willReturn(['extended' => 'config from ' . $configFilePath]);

        //$this->eventListener->__invoke($event);

        // Assert that the configuration has been extended
        // $expectedConfiguration = array_merge($siteConfiguration, ['extended' => 'config from ' . $configFilePath]);
        // self::assertEquals($expectedConfiguration, $event->getConfiguration());
    }*/

    /*  public function testInvokeWithoutSiteConfigExtends()
     {
         $siteIdentifier = 'gsb_frontend';
         $siteConfiguration = ['existing' => 'config'];

         $event = new SiteConfigurationLoadedEvent($siteIdentifier, $siteConfiguration);

         $this->eventListener->__invoke($event);

         // Assert that the configuration remains unchanged
         self::assertEquals($siteConfiguration, $event->getConfiguration());
         $this->resetSingletonInstances = true;
     } */
}
