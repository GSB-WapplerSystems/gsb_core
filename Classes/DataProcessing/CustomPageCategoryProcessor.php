<?php

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Christian Rath-Ulrich
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\DataProcessing;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class CustomPageCategoryProcessor extends AbstractCategoryProcessor implements DataProcessorInterface
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
        $uid = (int)$cObj->data['uid'];

        $languageId = 0;
        if (isset($processedData['data']['_PAGES_OVERLAY_LANGUAGE'])) {
            $languageId = $processedData['data']['_PAGES_OVERLAY_LANGUAGE'];
        }
        if (isset($processedData['data']['_PAGES_OVERLAY']) && $processedData['data']['_PAGES_OVERLAY'] === true) {
            $uid = (int)$processedData['data']['_PAGES_OVERLAY_UID'];
        }
        $processedData['pageCategories'] = $this->getPageCategories($languageId, $processorConfiguration['field'], $uid);

        if ($processedData['data']['main_category']) {
            $mainCategoryTitle = $this->getMainCategoryTitle($languageId, $processedData['data']['main_category']);
            $processedData['mainCategory'] = $mainCategoryTitle;
        }

        return $processedData;
    }
}
