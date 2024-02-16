<?php

namespace ITZBund\GsbCore\DataProcessing;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class CustomContendCategoryProcessor implements DataProcessorInterface
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
        if (str_contains($processedData['data']['tx_link'], 't3://page?uid=')) {
            $pid = preg_replace('/t3:\/\/page\?uid=/', '', $processedData['data']['tx_link']);

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
                    $queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->createNamedParameter('pages')),
                    $queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->createNamedParameter('categories')),
                    $queryBuilder->expr()->eq('mm.uid_foreign', $queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT))
                )
                ->executeQuery()
                ->fetchAllAssociative();
            $processedData['pageCategories'] = $resultPageCategories;

            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
            $resultMainCategory = $queryBuilder
                ->select('sys_category.uid', 'sys_category.title')
                ->from('sys_category')
                ->join(
                    'sys_category',
                    'pages',
                    'mm',
                    $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->quoteIdentifier('mm.main_category'))
                )
                ->where(
                    $queryBuilder->expr()->eq('mm.uid', $queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT))
                )
                ->executeQuery()
                ->fetchAssociative();
            $processedData['mainCategory'] = $resultMainCategory;
        }
        return $processedData;
    }
}
