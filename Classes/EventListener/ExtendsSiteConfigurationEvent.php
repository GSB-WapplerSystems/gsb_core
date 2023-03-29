<?php

declare(strict_types=1);

/*
 * This file is part of TYPO3 CMS-extension "gsb_template".
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 */

namespace ITZBund\GsbTemplate\EventListener;

use ITZBund\GsbTemplate\Configuration\ExtendSiteConfigurationRegistry;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationLoadedEvent;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ExtendsSiteConfigurationEvent
{
    private ExtendSiteConfigurationRegistry $registry;

    public function __construct(ExtendSiteConfigurationRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(SiteConfigurationLoadedEvent $event): void
    {
        $loader = GeneralUtility::makeInstance(YamlFileLoader::class);
        $siteConfiguration = $event->getConfiguration();
        $siteConfigExtends = $this->registry->get($event->getSiteIdentifier());
        if ($siteConfigExtends !== []) {
            foreach ($siteConfigExtends as $fileInfo) {
                $configuration = $loader->load(GeneralUtility::fixWindowsFilePath((string)$fileInfo), YamlFileLoader::PROCESS_IMPORTS);
                $siteConfiguration = array_merge($siteConfiguration, $configuration);
                // Maybe better?
                //ArrayUtility::mergeRecursiveWithOverrule($siteConfiguration, $configuration);
            }
        }
        $event->setConfiguration($siteConfiguration);
    }
}
