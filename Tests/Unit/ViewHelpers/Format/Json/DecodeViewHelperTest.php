<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers\Format\Json;

use ITZBund\GsbCore\ViewHelpers\Format\Json\DecodeViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class DecodeViewHelperTest extends UnitTestCase
{
    #[Test]
    #[DataProvider('callables')]
    #[TestDox('Render method $_dataName')]
    public function renderReturnsDecodedJson(
        string $encodedJson,
        bool $expectError,
        mixed $expectedResult,
    ): void {
        /*###########
        ## Arrange ##
        ###########*/
        /** RenderingContext **/
        $renderingContextMock = $this->getMockBuilder(RenderingContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        $decodeViewHelper = new DecodeViewHelper();

        /*#######
        ## Act ##
        #######*/
        $decodeViewHelper->setRenderingContext($renderingContextMock);
        $decodeViewHelper->initializeArguments();
        $decodeViewHelper->setArguments(['json' => $encodedJson]);

        /*##########
        ## Assert ##
        ##########*/
        if ($expectError) {
            self::expectException(\Exception::class);
            self::expectExceptionCode(1358440054);
        }

        $assert = $decodeViewHelper->render();

        self::assertEquals($expectedResult, $assert);
    }

    public static function callables(): \Generator
    {
        yield 'returns valid array from encoded JSON string \'$encodedJson\'.' => [
            '{"foo":"bar","bar":true,"baz":1,"foobar":null}',
            false,
            ['foo' => 'bar', 'bar' => true, 'baz' => 1, 'foobar' => null],
        ];
        yield 'returns empty array from encoded empty JSON string \'$encodedJson\'.' => [
            '{}',
            false,
            [],
        ];
        yield 'returns empty string string from encoded empty string \'\'.' => [
            '',
            false,
            '',
        ];
        yield 'throws exception from invalid encoded JSON string \'$encodedJson\'.' => [
            '{"foo":"\xB1","bar":true,"baz":1,"foobar":null}',
            true,
            null,
        ];
        yield 'throws exception from invalid encoded JSON string "$encodedJson".' => [
            "{'foo': 'bar'}",
            true,
            null,
        ];
    }
}
