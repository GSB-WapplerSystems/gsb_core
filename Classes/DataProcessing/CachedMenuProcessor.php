<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Thorsten Müller
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
    private string $configAs = '';
    private string $configIncludeSpacer = '';
    private string $configLevels = '';
    private string $configRootPageId = '';
    private string $configBreadcrumbAs = '';
    /**
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param mixed[] $contentObjectConfiguration
     * @param mixed[] $processorConfiguration
     * @param mixed[] $processedData
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
        $this->configAs = $processorConfiguration['as'] ?? 'menuMain';
        $this->configIncludeSpacer = $processorConfiguration['includeSpacer'] ?? '1';
        $this->configLevels = $processorConfiguration['levels'] ?? '4';
        $this->configRootPageId = $processorConfiguration['rootPageId'] ?? '0';
        $this->configBreadcrumbAs = $processorConfiguration['breadcrumbAs'] ?? 'breadcrumbMenu';

        if ($this->configRootPageId === '0') {
            return $processedData;
        }

        $processedData = $this->getCachedData($cObj, $contentObjectConfiguration, $processedData);
        $processedData = $this->setBreadcrumb($cObj, $contentObjectConfiguration, $processedData);

        return $processedData;
    }

    /**
     * @param ContentObjectRenderer $cObj
     * @param mixed[] $contentObjectConfiguration
     * @param mixed[] $processedData
     *
     * @return mixed[]
     */
    private function getCachedData(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processedData
    ): array {
        $processorConfiguration = [
            'as' => $this->configAs,
            'includeSpacer' => $this->configIncludeSpacer,
            'levels' => $this->configLevels,
            'special' => 'directory',
            'special.' => [
                'value' => $this->configRootPageId,
            ],
        ];

        $cache = $this->getCache();

        if ($cache->has($this->configRootPageId)) {
            $processedData[$this->configAs] = $cache->get($this->configRootPageId);

            return $processedData;
        }

        $menuProcessor = GeneralUtility::makeInstance(MenuProcessor::class);
        $processedData = $menuProcessor->process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
        $cache->set($this->configRootPageId, $processedData[$this->configAs]);

        return $processedData;
    }

    private function getCache(): FrontendInterface
    {
        return GeneralUtility::makeInstance(CacheManager::class)->getCache('gsb_core_menu');
    }

    /**
     * @param ContentObjectRenderer $cObj
     * @param mixed[] $contentObjectConfiguration
     * @param mixed[] $processedData
     *
     * @return mixed[]
     */
    private function setBreadcrumb(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processedData
    ): array {
        $menuProcessor = GeneralUtility::makeInstance(MenuProcessor::class);
        $rootLineConfig = ['as' => $this->configBreadcrumbAs, 'special' => 'rootline'];
        $processedData = $menuProcessor->process($cObj, $contentObjectConfiguration, $rootLineConfig, $processedData);
        $breadcrumb = [];

        foreach ($processedData[$this->configBreadcrumbAs] as $item) {
            $uid = $item['data']['uid'];
            $breadcrumb[$uid]['active'] = $item['active'];
            $breadcrumb[$uid]['current'] = $item['current'];
        }

        $processedData[$this->configAs] = $this->iterateCurrentActiveMenu($processedData[$this->configAs], $breadcrumb);

        return $processedData;
    }

    /**
     * @param mixed[] $processedMenuData
     * @param mixed[] $breadcrumb
     *
     * @return mixed[]
     */
    private function iterateCurrentActiveMenu(array $processedMenuData, array $breadcrumb): array
    {
        foreach ($processedMenuData as $key => $menu) {
            $processedMenuData[$key]['active'] = (int)($breadcrumb[$menu['data']['uid']]['active'] ?? 0);
            $processedMenuData[$key]['current'] = (int)($breadcrumb[$menu['data']['uid']]['current'] ?? 0);

            if (isset($menu['children'])) {
                $processedMenuData[$key]['children'] = $this->iterateCurrentActiveMenu($menu['children'], $breadcrumb);
            }
        }

        return $processedMenuData;
    }
}
