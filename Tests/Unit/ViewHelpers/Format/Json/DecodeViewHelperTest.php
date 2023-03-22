<?php

namespace ITZBund\GsbTemplate\Tests\Unit\ViewHelpers\Format\Json;

use ITZBund\GsbTemplate\Tests\Unit\ViewHelpers\AbstractViewHelperTestCase;
use ITZBund\GsbTemplate\ViewHelpers\Format\Json\DecodeViewHelper;

class DecodeViewHelperTest extends AbstractViewHelperTestCase
{
    /**
     * @test
     */
    public function returnsNullForEmptyArguments()
    {
        $result = DecodeViewHelper::renderStatic([''], function () {}, $this->renderingContext);
        self::assertEquals('', $result);
        $result = DecodeViewHelper::renderStatic(['json'=>''], function () {}, $this->renderingContext);
        self::assertEquals('', $result);
    }

    /**
     * @test
     */
    public function testReturnsExpectedValueForProvidedArguments()
    {
        $fixture = '{"foo":"bar","bar":true,"baz":1,"foobar":null}';

        $expected = [
            'foo' => 'bar',
            'bar' => true,
            'baz' => 1,
            'foobar' => null,
        ];

        $result = $this->executeViewHelper(['json' => $fixture]);
        self::assertEquals($expected, $result);
    }

    //adds in real error
    /**
     * @test
     */
    public function testThrowsExceptionForInvalidArgument()
    {
        $invalidJson = "{'foo': 'bar'}";
        $this->expectViewHelperException();
        $this->executeViewHelper(['json' => $invalidJson]);
    }
}
