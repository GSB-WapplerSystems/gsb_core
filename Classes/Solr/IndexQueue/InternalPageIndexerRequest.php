<?php

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
  * (c) Patrick Schriner <patrick.schriner@diemedialen.de> 2024
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace ITZBund\GsbCore\Solr\IndexQueue;

use ApacheSolrForTypo3\Solr\IndexQueue\PageIndexerRequest;
use Exception;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LogLevel;
use TYPO3\CMS\Core\Http\ServerRequestFactory;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Http\Application;

/**
 * "Internal" Index Queue Page Indexer request that does not use an actual request but
 * uses a seperate application request
 *
 * This is used to circumvent firewall issues in our deployment scenario where we might not be able to access the proper page via HTTP request
 */
class InternalPageIndexerRequest extends PageIndexerRequest
{
    /**
     * Fetches a page by sending the configured headers. Uses an internal request
     *
     * @throws Exception
     */
    protected function getUrl(string $url, array $headers, float $timeout): ResponseInterface
    {
        $subResponse = new Response(500);
        try {
            $subRequest = ServerRequestFactory::fromGlobals();
            foreach ($headers as $k => $v) {
                $splitHeader = explode(': ', $v);
                $headerKey = $splitHeader[0];
                $headerValue = $splitHeader[1];
                $subRequest = $subRequest->withAddedHeader($headerKey, $headerValue);
            }
            $subRequest = $subRequest->withUri(new Uri($url), true);

            $subResponse = $this->stashEnvironment(fn(): ResponseInterface => $this->sendSubRequest($subRequest, $this->indexQueueItem?->getRecordUid()));
            $subResponse->getBody()->rewind();
        } catch (\Exception $e) {
            // Log with INFO severity because this is what configured for Testing & Development contexts
            $this->logger->log(
                LogLevel::INFO,
                sprintf(
                    'Exception while fetching \'%s\': [%d] "%s"',
                    $url,
                    $e->getCode(),
                    $e->getMessage(),
                )
            );
        }
        return $subResponse;
    }

    /**
     * Stash and restore portions of the global environment around a subrequest callable.
     */
    protected function stashEnvironment(callable $fetcher): ResponseInterface
    {
        $parkedTsfe = $GLOBALS['TSFE'] ?? null;
        $GLOBALS['TSFE'] = null;
        $result = $fetcher();
        $GLOBALS['TSFE'] = $parkedTsfe;
        return $result;
    }

    /**
     * Sends an in-process subrequest.
     *
     * The $pageId is used to ensure the correct site is accessed.
     */
    protected function sendSubRequest(ServerRequestInterface $request, ?int $pageId): ResponseInterface
    {
        $container = GeneralUtility::getContainer();
        $application = $container->get(Application::class);
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pageId ?? 0);
        $request = $request->withAttribute('site', $site);
        return $application->handle($request);
    }
}
