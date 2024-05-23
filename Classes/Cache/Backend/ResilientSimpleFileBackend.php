<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Cache\Backend;

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Patrick Schriner
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend;

/**
 * This is a variant of the SimpleFileBackend that will not fail if require fails
 */
class ResilientSimpleFileBackend extends SimpleFileBackend
{
    /**
     * Loads PHP code from the cache and require it right away.
     *
     * @param string $entryIdentifier An identifier which describes the cache entry to load
     * @return mixed Potential return value from the include operation
     * @throws \InvalidArgumentException
     */
    public function require(string $entryIdentifier)
    {
        try {
            return parent::require($entryIdentifier);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
