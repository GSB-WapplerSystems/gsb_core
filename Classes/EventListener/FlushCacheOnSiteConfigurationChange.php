<?php

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Patrick Schriner
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\EventListener;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationBeforeWriteEvent;

/**
 * This event listener is used to trigger cache flushes when the site configuration has changed
 */
final class FlushCacheOnSiteConfigurationChange
{
    public function __construct(private readonly CacheManager $cacheManager) {}

    public function __invoke(SiteConfigurationBeforeWriteEvent $siteConfigurationBeforeWriteEvent): void
    {
        // we play "stupid" and simply clear all caches in the pages group
        $this->cacheManager->flushCachesInGroup('pages');
        $this->cacheManager->flushCachesInGroup('system');
        $this->cacheManager->flushCachesInGroup('all');
    }
}
