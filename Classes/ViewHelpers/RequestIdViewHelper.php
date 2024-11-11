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

use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class RequestIdViewHelper extends AbstractViewHelper
{
    public function render(): string
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if ($request instanceof ServerRequest && $request->hasHeader('x-request-id')) {
            $requestIdHeaders = $request->getHeader('x-request-id');
            return (string)reset($requestIdHeaders);
        }
        return '';
    }
}
