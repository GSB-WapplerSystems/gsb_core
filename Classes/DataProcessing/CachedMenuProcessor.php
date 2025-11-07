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
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\DataProcessing\MenuProcessor;

class CachedMenuProcessor implements DataProcessorInterface
{
    private string $processedDataKey = '';
    /**
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param array<mixed,mixed> $contentObjectConfiguration
     * @param array<mixed,mixed> $processorConfiguration
     * @param array<mixed,mixed> $processedData
     *
     * @return mixed[]
     *
     * @throws Exception
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $this->processedDataKey = $processorConfiguration['as'] ?? 'menuMain';

        $processedData = $this->getCachedData($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
        $processedData = $this->setBreadcrumb($cObj, $contentObjectConfiguration, $processedData);
        debug($processedData);die;

        return $processedData;
    }


    /**
     * @param ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     * @return mixed
     */
    private function getCachedData (
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $cacheIdentifier = $processorConfiguration['special.']['value'] ?? '0';
        $cache = $this->getCache();

        if ($cache->has($cacheIdentifier)) {
            debug('Cache hit for ' . $cacheIdentifier);
            $processedData[$this->processedDataKey] = $cache->get($cacheIdentifier);
        } else {
            debug('Cache miss for ' . $cacheIdentifier);
            $menuProcessor = GeneralUtility::makeInstance(MenuProcessor::class);
            $processedData = $menuProcessor->process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
            $cache->set($cacheIdentifier, $processedData[$this->processedDataKey], [], 2592000); // 30 days
        }

        return $processedData;
    }

    private function getCache(): FrontendInterface
    {
        return GeneralUtility::makeInstance(CacheManager::class)->getCache('gsb_core_menu');
    }

    /**
     * @return mixed[]
     */
    private function setBreadcrumb(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processedData
    ): array {
        $menuProcessor = GeneralUtility::makeInstance(MenuProcessor::class);
        $rootLineConfig = ['as' => 'breadcrumbMenu', 'special' => 'rootline'];
        $tmpProcessedData = $menuProcessor->process($cObj, $contentObjectConfiguration, $rootLineConfig, $processedData);
        $breadcrumb = [];

        foreach ($tmpProcessedData['breadcrumbMenu'] as $item) {
            $uid = $item['data']['uid'];
            $breadcrumb[$uid]['active'] = $item['active'];
            $breadcrumb[$uid]['current'] = $item['current'];
        }

        foreach ($processedData[$this->processedDataKey] as $menu) {

        }

        return $processedData;
    }

    private function iterateChildren(mixed $menuChildren, array $breadcrumb): array
    {
        foreach ($menuChildren as $key => $child) {
            if (isset($child['children']) && is_array($child['children'])) {
                $menuChildren[$key]['children'] = $this->iterateChildren($child['children'], $breadcrumb);
            }

            if (in_array($child['data']['uid'], $breadcrumb)) {
                $breadcrumbItem = $breadcrumb[$child['data']['uid']];
                $menuChildren[$key]['active'] = $breadcrumbItem['active'];
                $menuChildren[$key]['current'] = $breadcrumbItem['current'];
            }
        }

        return $menuChildren;
    }
}
