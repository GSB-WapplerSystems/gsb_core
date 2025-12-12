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

namespace ITZBund\GsbCore\Hooks;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class InlinePidSetter
{
    /**
     * Hook function for DataHandler
     *
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     *
     * @param DataHandler $dataHandler Reference to the main data handler object
     */
    public function processDatamap_afterAllOperations(
        DataHandler $dataHandler
    ): void {
        if ($dataHandler->datamap && array_key_exists('tx_gsbcore_hotspot', $dataHandler->datamap)) {
            // move new tt_content elements which are created inside of an imageMap hotspot to the configured page (gsbCore.inlineContentPid)
            foreach ($dataHandler->datamap['tx_gsbcore_hotspot'] as $hotspot) {
                if ($hotspot['popup']) {
                    $contentIDs = explode(',', $hotspot['popup']);
                    foreach ($contentIDs as $contentID) {
                        if (str_contains($contentID, 'NEW')) {
                            $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
                            try {
                                $site = $siteFinder->getSiteByPageId((int)$_GET['id']);
                                $targetPid = (int)($site->getSettings()->get('gsbCore.inlineContentPid') ?? 0);
                            } catch (\Throwable $e) {
                                $targetPid = 0;
                            }
                            $uid = $dataHandler->substNEWwithIDs[$contentID];
                            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                                ->getQueryBuilderForTable('tt_content');
                            $queryBuilder
                                ->update('tt_content')
                                ->set('pid', $targetPid)
                                ->where(
                                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER)),
                                )
                                ->executeQuery();
                        }
                    }
                }
            }
        }
    }
}
