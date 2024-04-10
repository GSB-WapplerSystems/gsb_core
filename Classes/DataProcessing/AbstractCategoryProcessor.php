<?php

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Christian Rath-Ulrich
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

abstract class AbstractCategoryProcessor
{
    /**
     * @param mixed $languageId
     * @param $field
     * @param int $uid
     * @return array<int, string>|null
     * @throws Exception
     */
    protected function getPageCategories(mixed $languageId, string $field, int $uid): ?array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
        $resultPageCategories = $queryBuilder
            ->select('sys_category.uid', 'sys_category.title', 'sys_category_l10n.title as localized_title')
            ->from('sys_category')
            ->join(
                'sys_category',
                'sys_category_record_mm',
                'mm',
                $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->quoteIdentifier('mm.uid_local'))
            )
            ->leftJoin(
                'sys_category',
                'sys_category',
                'sys_category_l10n',
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('sys_category_l10n.l10n_parent', $queryBuilder->quoteIdentifier('sys_category.uid')),
                    $queryBuilder->expr()->eq('sys_category_l10n.sys_language_uid', $queryBuilder->createNamedParameter($languageId, \PDO::PARAM_INT))
                )
            )
            ->where(
                $queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->createNamedParameter($field, \PDO::PARAM_STR)),
                $queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->createNamedParameter('categories', \PDO::PARAM_STR)),
                $queryBuilder->expr()->eq('mm.uid_foreign', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchAllAssociative();
        $pageCategories = [];
        if (count($resultPageCategories) > 0) {
            foreach ($resultPageCategories as $resultPageCategory) {
                $pageCategories[] = $resultPageCategory['localized_title'] ?? $resultPageCategory['title'];
            }
        }
        return $pageCategories;
    }

    /**
     * @param mixed $languageId
     * @param int $mainCategory
     * @return string
     * @throws Exception
     */
    protected function getMainCategoryTitle(mixed $languageId, int $mainCategory): string
    {
        $mainCategoryTitle = '';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
        $resultMainCategory = $queryBuilder
            ->select('sys_category.title', 'sys_category_l10n.title as localized_title')
            ->from('sys_category')
            ->leftJoin(
                'sys_category',
                'sys_category',
                'sys_category_l10n',
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('sys_category_l10n.l10n_parent', $queryBuilder->quoteIdentifier('sys_category.uid')),
                    $queryBuilder->expr()->eq('sys_category_l10n.sys_language_uid', $queryBuilder->createNamedParameter($languageId, \PDO::PARAM_INT))
                )
            )
            ->where(
                $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->createNamedParameter($mainCategory, \PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchAssociative();
        if (is_array($resultMainCategory)) {
            $mainCategoryTitle = $resultMainCategory['localized_title'] ?? $resultMainCategory['title'];
        }

        return $mainCategoryTitle;
    }
}
