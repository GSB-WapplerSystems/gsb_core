<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Matthias Peltzer, Christian Rath-Ulrich
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\Configuration\Discovery;

use ITZBund\GsbCore\Configuration\ExtendSiteConfigurationRegistry;
use Symfony\Component\Finder\Finder;
use TYPO3\CMS\Core\Package\PackageManager;

/**
 * Site configuration building is extended by hooking into the `\TYPO3\CMS\Core\Configuration\Event\SiteConfigurationLoadedEvent` event.
 *
 * Site configuration yaml fragments in `Configuration\SiteConfiguration\Extends\SITEIDENTIFIER\` will be merged into the site configuration `SITEIDENTIFIER`.
 *
 * Drawbacks
 *
 * Currently lacking a mechanism to add to *all* site configurations. You have to know the site identifier.
 */
final class ExtendSiteConfigurationLocator
{
    private PackageManager $packageManager;

    public function __construct(PackageManager $packageManager)
    {
        $this->packageManager = $packageManager;
    }

    public function locate(ExtendSiteConfigurationRegistry $registry): void
    {
        foreach ($this->packageManager->getActivePackages() as $package) {
            if (!file_exists($package->getPackagePath() . 'Configuration/SiteConfiguration/Extends')) {
                continue;
            }
            $finder = Finder::create()->files()->sortByName()->depth(0)->name('*.yaml')->in($package->getPackagePath() . 'Configuration/SiteConfiguration/Extends/*/');
            foreach ($finder as $fileInfo) {
                $identifier = basename($fileInfo->getPath());
                // the rtrim and ltrim is added to avoid double slashes in the path which occured in the cluster context
                $registry->add(
                    $identifier,
                    rtrim($fileInfo->getPath(), '/') . '/' . ltrim($fileInfo->getFilename(), '/')
                );
            }
        }
    }
}
