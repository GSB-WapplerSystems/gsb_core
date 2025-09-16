<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2025 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Thorsten Müller
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Page\Event\ResolveJavaScriptImportEvent;

final class ResolveJavaScriptImportEventListener
{
    #[AsEventListener]
    public function __invoke(ResolveJavaScriptImportEvent $event): void
    {
        if ($event->specifier === '@typo3/backend/settings/editor.js') {
            $event->importMap->includeImportsFor('@itzbund/gsb-core/site-sets-type/file.js');
        }
    }
}
