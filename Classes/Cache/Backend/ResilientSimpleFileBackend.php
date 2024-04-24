<?php

namespace ITZBund\GsbCore\Cache\Backend;

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Patrick Schriner
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
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
