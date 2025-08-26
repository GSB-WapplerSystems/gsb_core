<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers\Format\Json;

use ITZBund\GsbCore\ViewHelpers\Format\Json\DecodeViewHelper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;

class DecodeViewHelperTest extends UnitTestCase
{
    protected DecodeViewHelper $viewHelper;
    protected RenderingContext $renderingContext;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewHelper = new DecodeViewHelper();
        $this->renderingContext = new RenderingContext();
        $this->viewHelper->setRenderingContext($this->renderingContext);
    }

    #[Test]
    #[TestDox('DecodeViewHelper can be instantiated')]
    public function decodeViewHelperCanBeInstantiated(): void
    {
        self::assertInstanceOf(DecodeViewHelper::class, $this->viewHelper);
    }

    #[Test]
    #[TestDox('DecodeViewHelper extends AbstractViewHelper')]
    public function decodeViewHelperExtendsAbstractViewHelper(): void
    {
        self::assertInstanceOf(\TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper::class, $this->viewHelper);
    }

    #[Test]
    #[TestDox('Render decodes simple JSON string correctly')]
    public function renderDecodesSimpleJsonStringCorrectly(): void
    {
        $jsonString = '{"name": "John", "age": 30}';
        $expected = ['name' => 'John', 'age' => 30];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render decodes JSON array correctly')]
    public function renderDecodesJsonArrayCorrectly(): void
    {
        $jsonString = '["apple", "banana", "cherry"]';
        $expected = ['apple', 'banana', 'cherry'];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render decodes nested JSON structure correctly')]
    public function renderDecodesNestedJsonStructureCorrectly(): void
    {
        $jsonString = '{"user": {"name": "John", "address": {"city": "Berlin", "country": "Germany"}}, "active": true}';
        $expected = [
            'user' => [
                'name' => 'John',
                'address' => [
                    'city' => 'Berlin',
                    'country' => 'Germany'
                ]
            ],
            'active' => true
        ];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render decodes JSON with different data types correctly')]
    public function renderDecodesJsonWithDifferentDataTypesCorrectly(): void
    {
        $jsonString = '{"string": "test", "number": 123, "float": 45.67, "boolean": true, "null": null, "array": [1, 2, 3]}';
        $expected = [
            'string' => 'test',
            'number' => 123,
            'float' => 45.67,
            'boolean' => true,
            'null' => null,
            'array' => [1, 2, 3]
        ];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render handles empty JSON object correctly')]
    public function renderHandlesEmptyJsonObjectCorrectly(): void
    {
        $jsonString = '{}';
        $expected = [];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render handles empty JSON array correctly')]
    public function renderHandlesEmptyJsonArrayCorrectly(): void
    {
        $jsonString = '[]';
        $expected = [];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render handles null value correctly')]
    public function renderHandlesNullValueCorrectly(): void
    {
        $jsonString = 'null';
        $expected = null;

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render handles boolean values correctly')]
    public function renderHandlesBooleanValuesCorrectly(): void
    {
        $jsonString = 'true';
        $expected = true;

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render handles numeric values correctly')]
    public function renderHandlesNumericValuesCorrectly(): void
    {
        $jsonString = '42';
        $expected = 42;

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render handles string values correctly')]
    public function renderHandlesStringValuesCorrectly(): void
    {
        $jsonString = '"Hello World"';
        $expected = 'Hello World';

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render handles special characters in JSON correctly')]
    public function renderHandlesSpecialCharactersInJsonCorrectly(): void
    {
        $jsonString = '{"message": "Hello\nWorld\twith \"quotes\" and \\\\backslashes\\\\"}';
        $expected = ['message' => "Hello\nWorld\twith \"quotes\" and \\backslashes\\"];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render handles unicode characters correctly')]
    public function renderHandlesUnicodeCharactersCorrectly(): void
    {
        $jsonString = '{"text": "Hallo Welt mit Umlauten: äöüß"}';
        $expected = ['text' => 'Hallo Welt mit Umlauten: äöüß'];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render handles emoji characters correctly')]
    public function renderHandlesEmojiCharactersCorrectly(): void
    {
        $jsonString = '{"emoji": "🚀🌟🎉"}';
        $expected = ['emoji' => '🚀🌟🎉'];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    #[TestDox('Render throws exception for invalid JSON')]
    public function renderThrowsExceptionForInvalidJson(): void
    {
        $this->expectException(\JsonException::class);

        $invalidJson = '{"name": "John", "age": 30,}'; // Trailing comma

        $this->viewHelper->setArguments(['value' => $invalidJson]);
        $this->viewHelper->render();
    }

    #[Test]
    #[TestDox('Render throws exception for malformed JSON')]
    public function renderThrowsExceptionForMalformedJson(): void
    {
        $this->expectException(\JsonException::class);

        $malformedJson = '{"name": "John" "age": 30}'; // Missing comma

        $this->viewHelper->setArguments(['value' => $malformedJson]);
        $this->viewHelper->render();
    }

    #[Test]
    #[TestDox('Render throws exception for empty string')]
    public function renderThrowsExceptionForEmptyString(): void
    {
        $this->expectException(\JsonException::class);

        $this->viewHelper->setArguments(['value' => '']);
        $this->viewHelper->render();
    }

    #[Test]
    #[TestDox('Render throws exception for non-JSON string')]
    public function renderThrowsExceptionForNonJsonString(): void
    {
        $this->expectException(\JsonException::class);

        $this->viewHelper->setArguments(['value' => 'This is not JSON']);
        $this->viewHelper->render();
    }

    #[Test]
    #[TestDox('Render handles very large JSON correctly')]
    public function renderHandlesVeryLargeJsonCorrectly(): void
    {
        $largeArray = [];
        for ($i = 0; $i < 1000; $i++) {
            $largeArray["key_{$i}"] = "value_{$i}";
        }
        $jsonString = json_encode($largeArray);

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($largeArray, $result);
    }

    #[Test]
    #[TestDox('Render handles JSON with mixed array types correctly')]
    public function renderHandlesJsonWithMixedArrayTypesCorrectly(): void
    {
        $jsonString = '[1, "string", true, null, {"nested": "object"}, [1, 2, 3]]';
        $expected = [1, 'string', true, null, ['nested' => 'object'], [1, 2, 3]];

        $this->viewHelper->setArguments(['value' => $jsonString]);
        $result = $this->viewHelper->render();

        self::assertSame($expected, $result);
    }
}
