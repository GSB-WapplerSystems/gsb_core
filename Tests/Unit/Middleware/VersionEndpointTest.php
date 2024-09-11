<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Middleware;

use ITZBund\GsbCore\Middleware\VersionEndpoint;
use ITZBund\GsbCore\Utility\EnvironmentVersionsUtility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Frontend\Http\RequestHandler;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class VersionEndpointTest extends UnitTestCase
{
    #[Test]
    #[DataProvider('wrongPathOrWrongMethod')]
    public function versionEndpointMiddleWareReturnsEarlyWhenPathDoesOrMethodNotMatch($path, $method): void
    {
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $responseFactory->expects(self::never())->method('createResponse');

        $packageManager = $this->getMockBuilder(PackageManager::class)->disableOriginalConstructor()->getMock();

        $subject = new VersionEndpoint($responseFactory, new EnvironmentVersionsUtility(new Typo3Version(), $packageManager));
        $handler = $this->getMockBuilder(RequestHandler::class)->disableOriginalConstructor()->getMock();
        $handler->expects(self::once())->method('handle');

        $subject->process($this->createServerRequest($path, $method), $handler);
    }

    public static function wrongPathOrWrongMethod(): array
    {
        return [
            'path is wrong'  => [
                '/api/wrong',
                'GET',
            ],
            'method is wrong' => [
                '/api/version',
                'POST',
            ],
        ];
    }

    #[Test]
    public function versionMiddlewareReturnsResponseWithJsonBody(): void
    {
        putenv('GSB_VERSION=1');
        putenv('CONTAINER_VERSION=2');
        putenv('HELM_CHART_VERSION=3');

        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->onlyMethods(['createResponse'])->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(Response::class)->getMock();

        $stream = $this->getMockBuilder(Stream::class)->disableOriginalConstructor()->getMock();
        $stream->expects(self::once())
            ->method('write')
            ->with(
                json_encode([
                    'versions' => [
                        'gsb' => '1',
                        'container' => '2',
                        'helmChart' => '3',
                        'TYPO3' => (new Typo3Version())->getVersion(),
                        'packageCacheHash' => '42',
                    ],
                ])
            );

        $response->method('getBody')->willReturn($stream);
        $response->method('withHeader')->willReturn($response);
        $responseFactory->method('createResponse')->willReturn($response);

        $packageManager = $this->getMockBuilder(PackageManager::class)->disableOriginalConstructor()->getMock();
        $packageManager->method('getCacheIdentifier')->willReturn('42');

        $subject = new VersionEndpoint($responseFactory, new EnvironmentVersionsUtility(new Typo3Version(), $packageManager));
        $handler = $this->getMockBuilder(RequestHandler::class)->disableOriginalConstructor()->getMock();
        $handler->expects(self::never())->method('handle');

        $result = $subject->process($this->createServerRequest('/api/version', 'GET'), $handler);

        self::assertInstanceOf(ResponseInterface::class, $result);

        putenv('GSB_VERSION');
        putenv('CONTAINER_VERSION');
        putenv('HELM_CHART_VERSION');
    }

    #[Test]
    public function middleWareReturnsEmptyBodyWhenUtilityReturnsInvalidJson(): void
    {
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->onlyMethods(['createResponse'])->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(Response::class)->getMock();

        $stream = $this->getMockBuilder(Stream::class)->disableOriginalConstructor()->getMock();
        $stream->expects(self::never())->method('write');

        $response->method('getBody')->willReturn($stream);
        $response->method('withHeader')->willReturn($response);
        $responseFactory->method('createResponse')->willReturn($response);

        $packageManager = $this->getMockBuilder(PackageManager::class)->disableOriginalConstructor()->getMock();
        $packageManager->method('getCacheIdentifier');

        $utility = $this->getMockBuilder(EnvironmentVersionsUtility::class)->setConstructorArgs([new Typo3Version(), $packageManager])->getMock();
        $utility->method('getVersions')->willThrowException(new \JsonException('json exception'));

        $subject = new VersionEndpoint($responseFactory, $utility);
        $handler = $this->getMockBuilder(RequestHandler::class)->disableOriginalConstructor()->getMock();

        $result = $subject->process($this->createServerRequest('/api/version', 'GET'), $handler);

        self::assertInstanceOf(ResponseInterface::class, $result);
    }

    public function createServerRequest(string $path, string $method): ServerRequestInterface
    {
        $uri = new Uri('https://example.com' . $path);
        return new ServerRequest($uri, $method);
    }
}
