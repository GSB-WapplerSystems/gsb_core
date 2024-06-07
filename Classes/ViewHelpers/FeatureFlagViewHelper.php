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

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class FeatureFlagViewHelper extends AbstractViewHelper
{


    use CompileWithRenderStatic;

    public function initializeArguments()
    {
        $this->registerArgument('featureKey', 'string', 'The feature key to check', true);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $featureKey = $arguments['featureKey'];

        if (!array_key_exists($featureKey, $GLOBALS['TYPO3_CONF_VARS']['SYS']['features'])) {
            return true;
        }

        $featureValue = $GLOBALS['TYPO3_CONF_VARS']['SYS']['features'][$featureKey];

        if ($featureValue === false) {
            return false;
        }

        return true;
    }
}
