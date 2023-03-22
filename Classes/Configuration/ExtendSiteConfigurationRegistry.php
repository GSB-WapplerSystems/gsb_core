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

namespace ITZBund\GsbTemplate\Configuration;

use ITZBund\GsbTemplate\Configuration\Discovery\ExtendSiteConfigurationLocator;

class ExtendSiteConfigurationRegistry
{
    /**
     * @var array[]
     */
    protected array $extendSiteConfigurations = [];

    public function __construct(ExtendSiteConfigurationLocator $locator)
    {
        $locator->locate($this);
    }

    public function add(string $identifier, string $extendSiteConfigurationPath): void
    {
        if (!isset($this->extendSiteConfigurations[$identifier])) {
            $this->extendSiteConfigurations[$identifier] = [];
        }
        $this->extendSiteConfigurations[$identifier][] = $extendSiteConfigurationPath;
    }

    public function has(string $identifier): bool
    {
        return isset($this->extendSiteConfigurations[$identifier]);
    }

    public function get(string $identifier): array
    {
        if ($this->has($identifier)) {
            return $this->extendSiteConfigurations[$identifier];
        }
        return [];
    }

    public function getAll(): array
    {
        return $this->extendSiteConfigurations;
    }
}
