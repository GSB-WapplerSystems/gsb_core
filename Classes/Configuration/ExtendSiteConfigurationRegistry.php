<?php

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Kai Ole Hartwig <o.hartwig@moselwal.de> 2023
 * (c) Matthias Peltzer <matthias.peltzer@digitaspixelpark.com> 2023
 * (c) Christian Rath-Ulrich <christian.rath-ulrich@digitaspixelpark.com> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Configuration;

use ITZBund\GsbCore\Configuration\Discovery\ExtendSiteConfigurationLocator;

class ExtendSiteConfigurationRegistry
{
    /**
     * @var array<string, string[]>
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

    /**
     * @param string $identifier
     * @return array<string>
     */
    public function get(string $identifier): array
    {
        if ($this->has($identifier)) {
            return $this->extendSiteConfigurations[$identifier];
        }
        return [];
    }

    public function has(string $identifier): bool
    {
        return isset($this->extendSiteConfigurations[$identifier]);
    }

    /**
     * @return array<string, string[]>
     */
    public function getAll(): array
    {
        return $this->extendSiteConfigurations;
    }
}
