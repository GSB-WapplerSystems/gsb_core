<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2025 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Christian Rath-Ulrich
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Site;

use ITZBund\GsbClusteredCaching\Service\PayloadBasedCacheClear;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Configuration\SiteWriter;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Settings\SettingsFactory;
use TYPO3\CMS\Core\Settings\SettingsTypeRegistry;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Set\SetRegistry;
use TYPO3\CMS\Core\Site\SiteSettingsFactory;
use TYPO3\CMS\Core\Site\SiteSettingsService;

/**
 * Extended SiteSettingsService that clears the pages cache
 * when writeSettings() is called using cluster caching.
 */
final readonly class SiteSettingsServiceDecorator extends SiteSettingsService
{
    public function __construct(
        SiteWriter $siteWriter,
        #[Autowire(service: 'cache.core')]
        PhpFrontend $codeCache,
        SetRegistry $setRegistry,
        SiteSettingsFactory $siteSettingsFactory,
        SettingsFactory $settingsFactory,
        SettingsTypeRegistry $settingsTypeRegistry,
        FlashMessageService $flashMessageService,
        private readonly PayloadBasedCacheClear $payloadBasedCacheClear
    ) {
        parent::__construct(
            $siteWriter,
            $codeCache,
            $setRegistry,
            $siteSettingsFactory,
            $settingsFactory,
            $settingsTypeRegistry,
            $flashMessageService
        );
    }

    public function writeSettings(Site $site, array $settings): void
    {
        // Call parent method to write the settings
        parent::writeSettings($site, $settings);

        // Clear pages cache using cluster caching
        $payload = [
            'groups' => [
                ['group' => 'pages', 'flush' => true],
            ],
        ];

        $this->payloadBasedCacheClear->collectAndSendFlushCommands($payload);
    }
}
