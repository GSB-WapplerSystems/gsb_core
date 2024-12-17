<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Willi Wehmeier
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
use TYPO3\CMS\Core\LinkHandling\Exception\UnknownLinkHandlerException;
use TYPO3\CMS\Core\LinkHandling\Exception\UnknownUrnException;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Typolink\LinkFactory;
use TYPO3\CMS\Frontend\Typolink\UnableToLinkException;

class TargetPageDateProcessor implements DataProcessorInterface
{
    use PagesCacheAddingTrait;

    public function __construct(private readonly LinkFactory $linkFactory) {}

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
        $link = $cObj->data['tx_link'] ?? null;

        $targetVariableName = (string)$cObj->stdWrapValue('as', $processorConfiguration, 'tx_link_target_date');

        if (trim($link ?? '') === '') {
            return $processedData;
        }

        $page = $this->getPageFromTypolink($cObj, $link);
        $dateField = 'tstamp';

        if ($page !== null) {
            if ($page['SYS_LASTCHANGED'] > $page[$dateField]) {
                $dateField = 'SYS_LASTCHANGED';
            }
            if ($page['lastUpdated'] !== 0 && $page['lastUpdated'] !== '') {
                $dateField = 'lastUpdated';
            }
            $processedData[$targetVariableName] = $page[$dateField];
        }

        return $processedData;
    }

    /**
     * @return array<string,mixed>|null
     */
    private function getPageFromTypolink(ContentObjectRenderer $cObj, string $typolink): ?array
    {
        try {
            $this->linkFactory->create('', ['parameter' => $typolink], $cObj);
        } catch (UnableToLinkException $uTLe) {
            return null;
        }

        $linkService = GeneralUtility::makeInstance(LinkService::class);
        try {
            $decoded = $linkService->resolveByStringRepresentation($typolink);
        } catch (UnknownLinkHandlerException | UnknownUrnException $exception) {
            return null;
        }

        $pageUid = 0;
        if (($decoded['type'] ?? '') == 'page') {
            $pageUid = (int)$decoded['pageuid'];
        }
        if ($pageUid > 0) {
            $this->addPageUidCacheTag($pageUid);
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');

        $result = $queryBuilder
            ->select('*')
            ->from('pages')
            ->where($queryBuilder->expr()->eq('uid', $pageUid))
            ->executeQuery()
            ->fetchAssociative();

        return $result !== false ? $result : null;
    }

}
