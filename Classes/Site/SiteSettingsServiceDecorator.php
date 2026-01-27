<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2025 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Christian Rath-Ulrich, Thorsten Müller
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Site;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteSettingsService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Extended SiteSettingsService to clear the pages cache
 * when writeSettings() is called.
 */
readonly class SiteSettingsServiceDecorator extends SiteSettingsService
{
    /**
     * @param Site $site
     * @param array<string, mixed> $settings
     */
    public function writeSettings(Site $site, array $settings): void
    {
        // Call parent method to write the settings
        parent::writeSettings($site, $settings);

        if (ExtensionManagementUtility::isLoaded('gsb_clustered_caching')) {
            return;
        }

        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheManager->flushCachesInGroup('pages');
    }
}
