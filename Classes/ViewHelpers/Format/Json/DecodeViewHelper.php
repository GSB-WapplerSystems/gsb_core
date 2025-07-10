<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Christian Rath-Ulrich
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

declare(strict_types=1);

namespace ITZBund\GsbCore\ViewHelpers\Format\Json;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

/**
 * Class DecodeViewHelper
 */
class DecodeViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @codeCoverageIgnore
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('json', 'string', 'The JSON string to decode', true);
    }

    public function render()
    {
        $json = $this->arguments['json'] ?? null;

        if ($json === '' || $json === null) {
            return '';
        }

        $decodedValue = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('The provided argument is invalid JSON.', 1358440054);
        }

        return $decodedValue;
    }
}
