<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Christian Rath-Ulrich
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\DataProcessing;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class CustomContentCategoryProcessor extends AbstractCategoryProcessor implements DataProcessorInterface
{
    /**
     * @throws Exception
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        if (str_contains((string)($processedData['data']['tx_link'] ?? ''), 't3://page?uid=')) {
            $pid = preg_replace('/t3:\/\/page\?uid=/', '', (string)($processedData['data']['tx_link'] ?? ''));
            $languageId = $processedData['data']['sys_language_uid'];

            $processedData['pageCategories'] = $this->getPageCategories($languageId, 'pages', (int)$pid);

            $processedData['mainCategory'] = $this->getMainCategoryTitle($languageId, (int)$pid);
        }
        return $processedData;
    }
}
