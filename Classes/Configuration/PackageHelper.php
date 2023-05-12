<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\Configuration;

/*
 * This file is part of TYPO3 CMS-based extension "gsb_template".
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * Highly inspired by the "bolt" extension by b13.
 */

use TYPO3\CMS\Core\Package\Exception\UnknownPackageException;
use TYPO3\CMS\Core\Package\PackageInterface;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;

/**
 * Helper to render a dynamic selection of available site extensions
 * and to fetch a package for certain page
 */
class PackageHelper
{
    protected PackageManager $packageManager;
    protected SiteFinder $siteFinder;

    public function __construct(PackageManager $packageManager, SiteFinder $siteFinder)
    {
        $this->packageManager = $packageManager;
        $this->siteFinder = $siteFinder;
    }

    public function getSitePackageFromSite(Site $site): ?PackageInterface
    {
        $configuration = $site->getConfiguration();
        if (!isset($configuration['sitePackage'])) {
            return null;
        }
        $packageKey = (string)$configuration['sitePackage'];
        try {
            return $this->packageManager->getPackage($packageKey);
        } catch (UnknownPackageException $_) {
            return null;
        }
    }

    /**
     * "itemsProcFunc" method adding a list of available "*site*" extension
     * keys or "gsb_template" as select drop down items. Used in Site backend module.
     *
     * @param array[] $fieldDefinition
     */
    public function getSiteListForSiteModule(array &$fieldDefinition): void
    {
        $fieldDefinition['items'][] = [
            '-- None --',
            '',
        ];
        $currentValue = $fieldDefinition['row']['sitePackage'] ?? '';
        $gotCurrentValue = false;
        foreach ($this->packageManager->getActivePackages() as $package) {
            $packageKey = $package->getPackageKey();
            if ($this->isSitePackage($packageKey)) {
                $fieldDefinition['items'][] = [
                    0 => $packageKey,
                    1 => $packageKey,
                ];
                if ($currentValue === $packageKey) {
                    $gotCurrentValue = true;
                }
            }
        }
        if (!$gotCurrentValue && $currentValue !== '') {
            $fieldDefinition['items'][] = [
                0 => $currentValue,
                1 => $currentValue,
            ];
        }
    }

    public function isSitePackage(string $packageKey): bool
    {
        return str_contains($packageKey, 'site') || $packageKey === 'gsb_core';
    }
}
