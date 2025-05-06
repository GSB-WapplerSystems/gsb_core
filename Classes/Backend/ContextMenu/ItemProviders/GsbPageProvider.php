<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2025 Bundesrepublik Deutschland, vertreten durch das
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

namespace ITZBund\GsbCore\Backend\ContextMenu\ItemProviders;

use TYPO3\CMS\Backend\ContextMenu\ItemProviders\PageProvider;

/**
 * Context menu item provider for pages table
 *
 * The delete and cut options won't be provided for pages that have is_siteroot set
 */
class GsbPageProvider extends PageProvider
{
    public function getPriority(): int
    {
        return 101;
    }

    /**
     * Whether this provider should kick in
     */
    public function canHandle(): bool
    {
        return $this->table === 'pages';
    }

    /**
     * Checks if the page is allowed to be removed
     */
    protected function canBeDeleted(): bool
    {
        return !$this->isRecordSiteRoot() && parent::canBeDeleted();
    }

    /**
     * Checks if the page is allowed to can be cut
     */
    protected function canBeCut(): bool
    {
        return !$this->isRecordSiteRoot() && parent::canBeCut();
    }

    protected function isRecordSiteRoot(): bool
    {
        return (bool)$this->record['is_siteroot'];
    }
}
