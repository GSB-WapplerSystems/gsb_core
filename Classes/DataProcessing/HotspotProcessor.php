<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2025 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund.
 * Developed by sitegeist media solutions GmbH (https://www.sitegeist.de)
 * Developer: Martin Neumann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace ITZBund\GsbCore\DataProcessing;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\LinkHandling\TypoLinkCodecService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class HotspotProcessor implements DataProcessorInterface
{
    public function __construct(private readonly ConnectionPool $connectionPool, private readonly TypoLinkCodecService $typolinkCodecService) {}
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
            $urlParts = $this->typolinkCodecService->decode($hotspot['link']);
            $urlParts['typolink'] = $cObj->typoLink_URL([
                'parameter' => $hotspot['link'],
                'forceAbsoluteUrl' => true,
            ]);
            $hotspots[$key]['urlParts'] = $urlParts;

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
