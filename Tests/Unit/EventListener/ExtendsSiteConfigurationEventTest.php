<?php
namespace ITZBund\GsbCore\Tests\Unit\EventListener;

use Codeception\Test\Unit;
use ITZBund\GsbCore\EventListener\ExtendsSiteConfigurationEvent;
use ITZBund\GsbCore\Configuration\ExtendSiteConfigurationRegistry;
use ITZBund\GsbCore\Configuration\Discovery\ExtendSiteConfigurationLocator;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationLoadedEvent;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
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
        $mockedLoader->expects($this->any())
            ->method('load')
            ->willReturn(['extended' => 'config from ' . $configFilePath]);



        $this->eventListener->__invoke($event);

        // Assert that the configuration has been extended
        $expectedConfiguration = array_merge($siteConfiguration, ['extended' => 'config from ' . $configFilePath]);
        $this->assertEquals($expectedConfiguration, $event->getConfiguration());
    }

    public function testInvokeWithoutSiteConfigExtends()
    {
        $siteIdentifier = 'gsb_frontend';
        $siteConfiguration = ['existing' => 'config'];

        $event = new SiteConfigurationLoadedEvent($siteIdentifier, $siteConfiguration);

        $this->eventListener->__invoke($event);

        // Assert that the configuration remains unchanged
        $this->assertEquals($siteConfiguration, $event->getConfiguration());
        $this->resetSingletonInstances = true;

    }
}
