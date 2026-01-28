<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension by adesso SE.
 *
 * (c) 2026 Aphisit Chanathale <aphisit.chanathale@adesso.de>, adesso SE
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace ITZBund\GsbCore\DataProcessing;

use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class LinkInfoProcessor
 */
class LinkInfoProcessor implements DataProcessorInterface
{
    /**
     * process
     * @param ContentObjectRenderer $cObj
     * @param array<mixed> $contentObjectConfiguration
     * @param array<mixed> $processorConfiguration
     * @param array<mixed> $processedData
     * @return array<mixed>
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array
    {
        $fieldName = $processorConfiguration['field'] ?? 'header_link';
        $targetVariableName = $processorConfiguration['as'] ?? 'linkData';

        $linkString = $cObj->data[$fieldName] ?? $processedData['data'][$fieldName] ?? '';

        if (is_array($linkString) && count($linkString) === 0) {
            return $processedData;
        }

        try {
            $linkService = GeneralUtility::makeInstance(LinkService::class);
            $result = $linkService->resolve($linkString);

            $ariaLabel = LocalizationUtility::translate('linkTypeAriaLabel.4', 'gsb_core') ?? 'internal';
            $ariaCssClass = 'internal-link';

            switch ($result['type']) {
                case 'url':
                    $ariaLabel = LocalizationUtility::translate('linkTypeAriaLabel.1', 'gsb_core') ?? 'external';
                    $ariaCssClass = 'external-link';
                    break;
                case 'file':
                    $ariaLabel = LocalizationUtility::translate('linkTypeAriaLabel.5', 'gsb_core') ?? 'download';
                    $ariaCssClass = 'download';
                    break;
            }

            $result['ariaLabel'] = $ariaLabel;
            $result['ariaCssClass'] = $ariaCssClass;
            $processedData[$targetVariableName] = $result;
        } catch (\Exception $e) {
        }

        return $processedData;
    }
}
