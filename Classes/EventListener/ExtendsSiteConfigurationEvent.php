<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Patrick Schriner
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\EventListener;

use ITZBund\GsbCore\Configuration\ExtendSiteConfigurationRegistry;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationLoadedEvent;
use TYPO3\CMS\Core\Configuration\Loader\Exception\YamlParseException;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ExtendsSiteConfigurationEvent
{
    public function __construct(
        private readonly ExtendSiteConfigurationRegistry $registry,
        private readonly LoggerInterface $logger
    ) {}

    public function __invoke(SiteConfigurationLoadedEvent $event): void
    {
        $loader = GeneralUtility::makeInstance(YamlFileLoader::class);
        $siteConfiguration = $event->getConfiguration();
        $siteConfigExtendsAll = $this->registry->get('_all');
        foreach ($siteConfigExtendsAll as $fileInfo) {
            $this->addToYamlConfiguration($siteConfiguration, $loader, (string)$fileInfo);
        }

        $siteConfigExtends = $this->registry->get($event->getSiteIdentifier());
        foreach ($siteConfigExtends as $fileInfo) {
            $this->addToYamlConfiguration($siteConfiguration, $loader, (string)$fileInfo);
        }
        $event->setConfiguration($siteConfiguration);
    }

    /**
     * @param array<string,mixed> &$siteConfiguration
     */
    protected function addToYamlConfiguration(array &$siteConfiguration, YamlFileLoader $loader, string $filepath): void
    {
        try {
            $configuration = $loader->load(GeneralUtility::fixWindowsFilePath($filepath), YamlFileLoader::PROCESS_IMPORTS);
            ArrayUtility::mergeRecursiveWithOverrule($siteConfiguration, $configuration);
        } catch (YamlParseException $ype) {
            $this->logger->error('Could not load yaml file', ['exception' => $ype, 'file' => $filepath]);
        }
    }
}
