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

namespace ITZBund\GsbCore\EventListener;

use ITZBund\GsbCore\Utility\ExtendSiteUtility;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationBeforeWriteEvent;
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class SynchronizeLocalizedSiteSettings
{
    public function __invoke(SiteConfigurationBeforeWriteEvent $event): void
    {
        if (! GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-3288')) {
            return;
        }

        $settings = $event->getConfiguration();
        $requestBody = is_array($this->getRequest()->getParsedBody()) ? $this->getRequest()->getParsedBody() : [];
        $controlFields = $requestBody['control']['active'] ?? [];

        $settings = ExtendSiteUtility::removeSelectedNullableFields($settings, $controlFields);
        $updatedSettings = ExtendSiteUtility::copyToggleFieldsToLanguageConfigs($settings);

        $event->setConfiguration($updatedSettings);
    }

    protected function getRequest(): ServerRequest
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
