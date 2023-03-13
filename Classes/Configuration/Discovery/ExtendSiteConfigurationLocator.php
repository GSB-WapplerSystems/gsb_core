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

namespace ITZBund\GsbTemplate\Configuration\Discovery;

use ITZBund\GsbTemplate\Configuration\ExtendSiteConfigurationRegistry;
use Symfony\Component\Finder\Finder;
use TYPO3\CMS\Core\Package\PackageManager;

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
                $registry->add($identifier, $fileInfo->getPath() . '/' . $fileInfo->getFilename());
            }
        }
    }
}
