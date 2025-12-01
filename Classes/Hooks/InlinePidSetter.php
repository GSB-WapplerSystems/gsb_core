<?php

declare(strict_types=1);

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
        if ($dataHandler->datamap && is_array($dataHandler->datamap['tx_gsbcore_hotspot'])) {
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
