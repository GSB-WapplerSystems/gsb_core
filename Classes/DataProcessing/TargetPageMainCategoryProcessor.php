<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Marco Luig
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\DataProcessing;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Typolink\LinkFactory;
use TYPO3\CMS\Frontend\Typolink\UnableToLinkException;

class TargetPageMainCategoryProcessor implements DataProcessorInterface
{
    /**
     * @param array<mixed> $contentObjectConfiguration
     * @param array<mixed> $processorConfiguration
     * @param array<mixed> $processedData
     * @return array<string,mixed>
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $linkFactory = GeneralUtility::makeInstance(LinkFactory::class);

        $link = $cObj->data['tx_link'] ?? null;
        $targetVariableName = (string)$cObj->stdWrapValue('as', $processorConfiguration, 'tx_link_target_main_category');

        if (trim($link ?? '') === '') {
            return $processedData;
        }

        $category = $this->getCategoryFromTypolink($cObj, $link, $linkFactory);

        if ($category !== null) {
            $processedData[$targetVariableName] = $category;
        }

        return $processedData;
    }

    /**
     * Extracts the complete 'sys_category' object from the page referenced by TypoLink.
     *
     * @return array<string,mixed>|null
     */
    private function getCategoryFromTypolink(ContentObjectRenderer $cObj, string $typolink, LinkFactory $linkFactory): ?array
    {
        try {
            $linkConfiguration = $linkFactory->create('', ['parameter' => $typolink], $cObj);
        } catch (UnableToLinkException $uTLe) {
            return null;
        }

        if ($linkConfiguration->getType() !== 'page') {
            return null;
        }

        $matches = [];
        preg_match('/^t3:\/\/page\?uid=([0-9]+)#{0,1}[0-9a-z-]*$/', $typolink, $matches);

        if (count($matches) !== 2) {
            return null;
        }

        $pageUid = (int)$matches[1];
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');

        $result = $queryBuilder
            ->select('main_category')
            ->from('pages')
            ->where($queryBuilder->expr()->eq('uid', $pageUid))
            ->executeQuery()
            ->fetchOne();

        if ($result === false || (int)$result === 0) {
            return null;
        }

        $categoryUid = (int)$result;
        $categoryQueryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');

        $category = $categoryQueryBuilder
            ->select('*')
            ->from('sys_category')
            ->where($categoryQueryBuilder->expr()->eq('uid', $categoryUid))
            ->executeQuery()
            ->fetchAssociative();

        return $category !== false ? $category : null;
    }
}
