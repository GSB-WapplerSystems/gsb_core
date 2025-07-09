<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Patrick Schriner
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

declare(strict_types=1);

namespace ITZBund\GsbCore\ViewHelpers;

use TYPO3\CMS\Core\Configuration\Features;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class FeatureFlagViewHelper extends AbstractViewHelper
{
    public function __construct(protected readonly Features $features) {}

    /**
     * @codeCoverageIgnore
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('featureKey', 'string', 'The feature key to check', true);
    }

    public function render(): string
    {
        $key = $this->arguments['featureKey'];

        return $this->features->isFeatureEnabled($key) ? '1' : '0';
    }
}
