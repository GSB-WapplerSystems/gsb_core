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
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class HotspotProcessor implements DataProcessorInterface
{
    public function __construct(private readonly ConnectionPool $connectionPool) {}
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
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_gsbcore_hotspot');
        $hotspots = $queryBuilder
            ->select('*')
            ->from('tx_gsbcore_hotspot')
            ->where($queryBuilder->expr()->eq('imagemap', (int)$processedData['data']['uid']))
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($hotspots as $key => $hotspot) {
            $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
            $hotspots[$key]['contents'] = $queryBuilder
                ->select('*')
                ->from('tt_content')
                ->where($queryBuilder->expr()->eq('hotspot_popup', (int)$hotspot['uid']))
                ->executeQuery()
                ->fetchAllAssociative();
        }
        $processedData['hotspots'] = $hotspots;
        return $processedData;
    }
}
