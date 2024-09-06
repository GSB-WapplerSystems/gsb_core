<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers\Format\Json;

use ITZBund\GsbCore\ViewHelpers\Format\Json\DecodeViewHelper;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class DecodeViewHelperTest extends UnitTestCase
{
    #[Test]
    public function viewHelperReturnsNullForEmptyArguments()
    {
        $renderingContext = $this->getRenderingContextMock();

        $result = DecodeViewHelper::renderStatic([''], function () {}, $renderingContext);
        self::assertEquals('', $result);
        $result = DecodeViewHelper::renderStatic(['json' => ''], function () {}, $renderingContext);
        self::assertEquals('', $result);
    }

    #[Test]
    public function viewHelperReturnsExpectedValueForProvidedArguments()
    {
        $fixture = '{"foo":"bar","bar":true,"baz":1,"foobar":null}';

        $expected = [
            'foo' => 'bar',
            'bar' => true,
            'baz' => 1,
            'foobar' => null,
        ];
        $renderingContext = $this->getRenderingContextMock();

        $result = DecodeViewHelper::renderStatic(['json' => $fixture], function () {}, $renderingContext);
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function viewHelperThrowsExceptionForInvalidArgument()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(1358440054);

        $invalidJson = "{'foo': 'bar'}";

        $renderingContext = $this->getRenderingContextMock();

        DecodeViewHelper::renderStatic(['json' => $invalidJson], function () {}, $renderingContext);
    }

    public function getRenderingContextMock(): RenderingContext
    {
        return $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
    }
}
