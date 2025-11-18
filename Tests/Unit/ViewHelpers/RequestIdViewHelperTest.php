<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2025 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Thorsten Müller
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use ITZBund\GsbCore\ViewHelpers\RequestIdViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class RequestIdViewHelperTest extends UnitTestCase
{
    private const SERVER_REQUEST_HEADER = 'isSet';

    #[Test]
    #[DataProvider('requestIdViewHelperDataProvider')]
    #[TestDox('Render method while $_dataName returns the string "$expectedResult"')]
    public function requestIdViewHelperTestReturnsStringZeroIfFeatureDoesNotExist(
        bool $serverRequestSet,
        bool $serverRequestHasHeader,
        string $expectedResult
    ): void {
        /*###########
        ## Arrange ##
        ###########*/
        /** ServerRequest **/
        if ($serverRequestSet) {
            $serverRequestMock = $this->getMockBuilder(ServerRequest::class)
                ->disableOriginalConstructor()
                ->getMock();
            $serverRequestMock
                ->method('hasHeader')
                ->willReturn($serverRequestHasHeader);
            $serverRequestMock
                ->method('getHeader')
                ->willReturn([self::SERVER_REQUEST_HEADER]);
        } else {
            $serverRequestMock = null;
        }

        $GLOBALS['TYPO3_REQUEST'] = $serverRequestMock;
        $subject = new RequestIdViewHelper();

        /*#######
        ## Act ##
        #######*/
        $assert = $subject->render();

        /*##########
        ## Assert ##
        ##########*/
        self::assertEquals($expectedResult, $assert);
    }

    public static function requestIdViewHelperDataProvider(): \Generator
    {
        yield 'Request is set but has no header' => [
            true,
            false,
            '',
        ];
        yield 'Request is not set but has header' => [
            false,
            true,
            '',
        ];
        yield 'Request is not set and has no header' => [
            false,
            false,
            '',
        ];
        yield 'Request is set and has a header' => [
            true,
            true,
            self::SERVER_REQUEST_HEADER,
        ];
    }
}
