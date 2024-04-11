<?php

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Patrick Schriner
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\EventListener;

use ITZBund\GsbCore\Configuration\ExtendSiteConfigurationRegistry;
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
                ArrayUtility::mergeRecursiveWithOverrule($siteConfiguration, $configuration);
            }
        }
        $event->setConfiguration($siteConfiguration);
    }
}
