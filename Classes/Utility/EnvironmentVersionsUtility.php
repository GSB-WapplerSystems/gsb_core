<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Willi Wehmeier
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Utility;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Package\PackageManager;

class EnvironmentVersionsUtility
{
    public function __construct(private readonly Typo3Version $typo3Version, private readonly PackageManager $packageManager) {}

    /**
     * @return array<string,array<string,?string>>
     */
    public function getVersions(): array
    {
        return [
            'versions' => [
                'gsb' => $this->getGsbVersion(),
                'container' => $this->getContainerVersion(),
                'helmChart' => $this->getHelmChartVersion(),
                'TYPO3' => $this->typo3Version->getVersion(),
                'packageCacheHash' => $this->packageManager->getCacheIdentifier(),
            ],
        ];
    }

    protected function getGsbVersion(): ?string
    {
        $gsbVersion = getenv('GSB_VERSION');

        if ($gsbVersion === false) {
            return null;
        }

        return $gsbVersion;
    }

    protected function getContainerVersion(): ?string
    {
        $containerVersion = getenv('CONTAINER_VERSION');

        if ($containerVersion === false) {
            return null;
        }

        return $containerVersion;
    }

    protected function getHelmChartVersion(): ?string
    {
        $helmChartVersion = getenv('HELM_CHART_VERSION');

        if ($helmChartVersion === false) {
            return null;
        }

        return $helmChartVersion;
    }
}
