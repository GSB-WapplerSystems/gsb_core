<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\EventListener;

interface SiteConfigurationLocatorInterface
{
    /**
     * Locate the site configuration file path based on the site identifier.
     *
     * @param string $siteIdentifier The site identifier.
     * @return string|null The file path of the site configuration file, or null if not found.
     */
    public function locateSiteConfiguration(string $siteIdentifier): ?string;
}
