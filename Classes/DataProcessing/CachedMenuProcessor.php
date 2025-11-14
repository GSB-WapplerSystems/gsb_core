<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

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

namespace ITZBund\GsbCore\DataProcessing;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\UserAspect;
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
    private UserAspect $userAspect;
    private FrontendInterface $cache;

    public function __construct(
        private readonly Context $context,
        private readonly CacheManager $cacheManager,
        private readonly MenuProcessor $menuProcessor,
    ) {
        $this->userAspect = $this->context->getAspect('frontend.user');
        $this->cache = $this->cacheManager->getCache('gsb_core_menu');
    }

    /**
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
        $this->setConfig($processorConfiguration);

        if ($this->configRootPageId === '0') {
            return $processedData;
        }

        $processedData = $this->processMenuCache($cObj, $contentObjectConfiguration, $processedData);
        $processedData = $this->processBreadcrumb($cObj, $contentObjectConfiguration, $processedData);
        $rootLine = $this->getRootLineByBreadcrumb($processedData[$this->configBreadcrumbAs]);
        $processedData[$this->configAs] = $this->processCachedMenuDataIterative($processedData[$this->configAs], $rootLine);

        return $processedData;
    }

    /**
     * @param mixed[] $processorConfiguration
     */
    private function setConfig(array $processorConfiguration): void
    {
        $this->configAs = $processorConfiguration['as'] ?? 'menuMain';
        $this->configIncludeSpacer = $processorConfiguration['includeSpacer'] ?? '1';
        $this->configLevels = $processorConfiguration['levels'] ?? '4';
        $this->configRootPageId = $processorConfiguration['rootPageId'] ?? '0';
        $this->configBreadcrumbAs = $processorConfiguration['breadcrumbAs'] ?? 'breadcrumbMenu';
    }

    /**
     * @param ContentObjectRenderer $cObj
     * @param mixed[] $contentObjectConfiguration
     * @param mixed[] $processedData
     *
     * @return mixed[]
     */
    private function processMenuCache(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processedData
    ): array {
        $processorConfiguration = [
            'as' => $this->configAs,
            'includeSpacer' => $this->configIncludeSpacer,
            'levels' => $this->configLevels,
            'showAccessRestrictedPages' => '1',
            'special' => 'directory',
            'special.' => [
                'value' => $this->configRootPageId,
            ],
        ];

        if ($this->cache->has($this->configRootPageId)) {
            $processedData[$this->configAs] = $this->cache->get($this->configRootPageId);

            return $processedData;
        }

        $processedData = $this->menuProcessor->process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
        $this->cache->set($this->configRootPageId, $processedData[$this->configAs]);

        return $processedData;
    }

    /**
     * @param ContentObjectRenderer $cObj
     * @param mixed[] $contentObjectConfiguration
     * @param mixed[] $processedData
     *
     * @return mixed[]
     */
    private function processBreadcrumb(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processedData
    ): array {
        return $this->menuProcessor->process(
            $cObj,
            $contentObjectConfiguration,
            ['as' => $this->configBreadcrumbAs, 'special' => 'rootline'],
            $processedData
        );
    }

    /**
     * @param mixed[] $processedBreadcrumbData
     *
     * @return array<int, array<string, int>>
     */
    private function getRootLineByBreadcrumb(array $processedBreadcrumbData): array
    {
        $rootLine = [];

        foreach ($processedBreadcrumbData as $item) {
            $uid = $item['data']['uid'];
            $rootLine[$uid]['active'] = $item['active'];
            $rootLine[$uid]['current'] = $item['current'];
        }

        return $rootLine;
    }

    /**
     * @param mixed[] $processedMenuData
     * @param array<int, array<string, int>> $rootLine
     *
     * @return mixed[]
     */
    private function processCachedMenuDataIterative(array $processedMenuData, array $rootLine): array
    {
        foreach ($processedMenuData as $key => $menu) {
            if (!$this->isMenuAccessibleForUser((int)$menu['data']['fe_group'])) {
                unset($processedMenuData[$key]);

                continue;
            }

            $menuUid = $menu['data']['uid'];
            $processedMenuData[$key]['active'] = (int)($rootLine[$menuUid]['active'] ?? 0);
            $processedMenuData[$key]['current'] = (int)($rootLine[$menuUid]['current'] ?? 0);

            if (isset($menu['children'])) {
                $processedMenuData[$key]['children'] = $this->processCachedMenuDataIterative($menu['children'], $rootLine);
            }
        }

        return $processedMenuData;
    }

    private function isMenuAccessibleForUser(int $allowedGroupId): bool
    {
        $currentUserGroups = $this->userAspect->getGroupIds();

        return in_array($allowedGroupId, $currentUserGroups, true);
    }
}
