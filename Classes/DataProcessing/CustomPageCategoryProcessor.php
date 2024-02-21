<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Christian Rath-Ulrich <christian.rath-ulrich@digitaspixelpark.com> 2024
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
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class CustomPageCategoryProcessor implements DataProcessorInterface
{
    /**
     * @throws Exception
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $uid = (int)$cObj->data['uid'];
        if (isset($processedData['data']['_PAGES_OVERLAY']) && $processedData['data']['_PAGES_OVERLAY'] === true) {
            $uid = (int)$processedData['data']['_PAGES_OVERLAY_UID'];
        }
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
        $resultPageCategories = $queryBuilder
            ->select('sys_category.uid', 'sys_category.title')
            ->from('sys_category')
            ->join(
                'sys_category',
                'sys_category_record_mm',
                'mm',
                $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->quoteIdentifier('mm.uid_local'))
            )
            ->where(
                $queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->createNamedParameter($processorConfiguration['field'])),
                $queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->createNamedParameter('categories')),
                $queryBuilder->expr()->eq('mm.uid_foreign', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchAllAssociative();

        if ($processedData['data']['main_category']) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
            $resultMainCategory = $queryBuilder
                ->select('sys_category.title')
                ->from('sys_category')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($processedData['data']['main_category'], \PDO::PARAM_INT))
                )
                ->executeQuery()
                ->fetchOne();
            $processedData['mainCategory'] = $resultMainCategory;
        }
        $processedData['pageCategories'] = $resultPageCategories;

        return $processedData;
    }
}
