<?php

namespace ITZBund\GsbCore\Tests\Unit\EventListener;

use Codeception\Test\Unit;
use ITZBund\GsbCore\EventListener\ExtendsSiteConfigurationEvent;
use ITZBund\GsbCore\Configuration\ExtendSiteConfigurationRegistry;
use ITZBund\GsbCore\Configuration\Discovery\ExtendSiteConfigurationLocator;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationLoadedEvent;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class ExtendsSiteConfigurationEventTest extends Unit
{
    /**
     * @var ExtendSiteConfigurationRegistry
     */
    private $registry;

    /**
     * @var ExtendsSiteConfigurationEvent
     */
    private $eventListener;

    protected function _before()
    {
        $locator = new ExtendSiteConfigurationLocator(); // Verwenden Sie eine echte Instanz des Locators
        $this->registry = new ExtendSiteConfigurationRegistry($locator);
        $this->eventListener = new ExtendsSiteConfigurationEvent($this->registry);
    }

    public function testInvokeWithSiteConfigExtends()
    {
        $siteIdentifier = 'example_site';
        $configFilePath = 'path/to/config.yaml';
        $siteConfiguration = ['existing' => 'config'];

        $event = new SiteConfigurationLoadedEvent($siteIdentifier, $siteConfiguration);
        $this->registry->add($siteIdentifier, $configFilePath);

        $mockedLoader = $this->make(YamlFileLoader::class, [
            'load' => function () use ($configFilePath) {
                // Simulating the loaded configuration
                return ['extended' => 'config from ' . $configFilePath];
            }
        ]);

        $mockedUtility = $this->make(GeneralUtility::class, [
            'makeInstance' => $mockedLoader
        ]);

        $this->eventListener->__invoke($event);

        // Assert that the configuration has been extended
        $expectedConfiguration = array_merge($siteConfiguration, ['extended' => 'config from ' . $configFilePath]);
        $this->assertEquals($expectedConfiguration, $event->getConfiguration());
    }

    public function testInvokeWithoutSiteConfigExtends()
    {
        $siteIdentifier = 'example_site';
        $siteConfiguration = ['existing' => 'config'];

        $event = new SiteConfigurationLoadedEvent($siteIdentifier, $siteConfiguration);

        $this->eventListener->__invoke($event);

        // Assert that the configuration remains unchanged
        $this->assertEquals($siteConfiguration, $event->getConfiguration());
    }
}
