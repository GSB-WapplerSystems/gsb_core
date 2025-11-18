<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Middleware;

use ITZBund\GsbCore\Middleware\VersionEndpoint;
use ITZBund\GsbCore\Utility\EnvironmentVersionsUtility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class VersionEndpointTest extends UnitTestCase
{
    /**
     * @param string $path
     * @param string $method
     * @param string[][] $versionUtilityReturn
     * @param string $streamReturn
     * @param bool $equals
     */
    #[Test]
    #[DataProvider('processDataProvider')]
    #[TestDox('Return a Response containing a Stream with Version data $_dataName')]
    public function processReturnsResponseInterface(
        string $path,
        string $method,
        array $versionUtilityReturn,
        string $streamReturn,
        bool $equals
    ): void {
        /*###########
        ## Arrange ##
        ###########*/
        /** Response **/
        $responseMock = $this->createResponseMock($streamReturn);
        /** ServerRequest **/
        /** @var ServerRequestInterface $serverRequestMock */
        $serverRequestMock = $this->createServerRequestMock($path, $method);

        /** RequestHandler **/
        $requestHandlerMock = $this->getMockBuilder(RequestHandlerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $requestHandlerMock
            ->method('handle')
            ->willReturn($responseMock);

        /** ResponseFactory **/
        $responseFactoryMock = $this->getMockBuilder(ResponseFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $responseFactoryMock
            ->method('createResponse')
            ->willReturn($responseMock);

        /** Response **/
        $environmentVersionsUtilityMock = $this->getMockBuilder(EnvironmentVersionsUtility::class)
            ->disableOriginalConstructor()
            ->getMock();
        $environmentVersionsUtilityMock
            ->method('getVersions')
            ->willReturn($versionUtilityReturn);

        $subject = new VersionEndpoint($responseFactoryMock, $environmentVersionsUtilityMock);

        /*#######
        ## Act ##
        #######*/
        $assert = $subject->process($serverRequestMock, $requestHandlerMock);

        /*##########
        ## Assert ##
        ##########*/
        self::assertInstanceOf(StreamInterface::class, $assert->getBody());

        if ($equals) {
            self::assertSame(json_encode($versionUtilityReturn), $assert->getBody()->getContents());
        } else {
            self::assertNotSame(json_encode($versionUtilityReturn), $assert->getBody()->getContents());
        }
    }

    public static function processDataProvider(): \Generator
    {
        yield 'fails with wrong path but correct method' => [
            '/api/wrong',
            'GET',
            ['versions' => ['gsb' => '11']],
            '',
            false,
        ];
        yield 'fails with correct path but wrong method' => [
            '/api/version',
            'POST',
            ['versions' => ['gsb' => '11']],
            '',
            false,
        ];
        yield 'fails with wrong path and wrong method' => [
            '/api/wrong',
            'POST',
            ['versions' => ['gsb' => '11']],
            '',
            false,
        ];
        yield 'works with correct method and path and valid array for json_encode' => [
            '/api/version',
            'GET',
            ['versions' => ['gsb' => '11']],
            json_encode(['versions' => ['gsb' => '11']]),
            true,
        ];
        yield 'fails with correct method and path but invalid array for json_encode' => [
            '/api/version',
            'GET',
            ["\xB1"],
            '',
            false,
        ];
    }

    protected function createResponseMock(string $streamReturn): MockObject|ResponseInterface
    {
        /** Stream **/
        $streamMock = $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $streamMock
            ->method('getContents')
            ->willReturn($streamReturn);

        /** Response **/
        $responseMock = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
        $responseMock
            ->method('getBody')
            ->willReturn($streamMock);
        $responseMock
            ->method('withHeader')
            ->willReturn($responseMock);

        return $responseMock;
    }

    protected function createServerRequestMock(string $path, string $method): MockObject|ServerRequestInterface
    {
        /** Uri **/
        $uriMock = $this->getMockBuilder(UriInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uriMock
            ->method('getPath')
            ->willReturn($path);

        /** ServerRequest **/
        $serverRequestMock = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $serverRequestMock
            ->method('getUri')
            ->willReturn($uriMock);
        $serverRequestMock
            ->method('getMethod')
            ->willReturn($method);

        return $serverRequestMock;
    }
}
