<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2025 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Christian Rath-Ulrich
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

use ITZBund\GsbCore\Event\GetTrademarkLogoEvent;
use ITZBund\GsbCore\Event\GetTrademarkTextEvent;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class TrademarkViewHelper extends AbstractViewHelper
{
    public function __construct(
        private readonly EventDispatcher $eventDispatcher
    ) {
        $this->escapeOutput = false;
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('type', 'string', 'The name of the asset', true);
    }

    public function render(): string
    {
        $assetType = $this->arguments['type'];
        $event = null;
        switch ($assetType) {
            case 'logo':
                $event = new GetTrademarkLogoEvent();
                break;
            case 'text':
                $event = new GetTrademarkTextEvent();
                break;
        }
        if ($event !== null) {
            $this->eventDispatcher->dispatch($event);
            return $event->getValue();
        }
        return '';
    }
}
