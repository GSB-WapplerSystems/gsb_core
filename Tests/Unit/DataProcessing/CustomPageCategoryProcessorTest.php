<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\DataProcessing;

use ITZBund\GsbCore\DataProcessing\CustomPageCategoryProcessor;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CustomPageCategoryProcessorTest extends UnitTestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject|(CustomPageCategoryProcessor&\PHPUnit\Framework\MockObject\MockObject) $processor */
    protected CustomPageCategoryProcessor $processor;
    protected ContentObjectRenderer $contentObjectRenderer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->getMockBuilder(CustomPageCategoryProcessor::class)
            ->onlyMethods(['getPageCategories', 'getMainCategoryTitle'])
            ->getMock();

        $this->contentObjectRenderer = $this->createMock(ContentObjectRenderer::class);
    }

    #[Test]
    #[TestDox('CustomPageCategoryProcessor can be instantiated')]
    public function customPageCategoryProcessorCanBeInstantiated(): void
    {
        self::assertInstanceOf(CustomPageCategoryProcessor::class, $this->processor);
    }

    #[Test]
    #[TestDox('Process returns processed data with page categories')]
    public function processReturnsProcessedDataWithPageCategories(): void
    {
        $contentObjectConfiguration = [];
        $processorConfiguration = ['field' => 'categories'];
        $processedData = ['data' => ['uid' => 123]];
        $expectedCategories = [['title' => 'Category 1'], ['title' => 'Category 2']];

        $this->contentObjectRenderer->data = ['uid' => 123];
        $this->processor->method('getPageCategories')
            ->with(0, 'categories', 123)
            ->willReturn($expectedCategories);

        $result = $this->processor->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        self::assertArrayHasKey('pageCategories', $result);
        self::assertSame($expectedCategories, $result['pageCategories']);
    }

    #[Test]
    #[TestDox('Process handles localized pages correctly')]
    public function processHandlesLocalizedPagesCorrectly(): void
    {
        $contentObjectConfiguration = [];
        $processorConfiguration = ['field' => 'categories'];
        $processedData = [
            'data' => [
                'uid' => 123,
                '_PAGES_OVERLAY' => true,
                '_LOCALIZED_UID' => 456,
            ],
        ];
        $expectedCategories = [['title' => 'Localized Category']];

        $this->contentObjectRenderer->data = ['uid' => 123];
        $this->processor->method('getPageCategories')
            ->with(0, 'categories', 456)
            ->willReturn($expectedCategories);

        $result = $this->processor->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        self::assertArrayHasKey('pageCategories', $result);
        self::assertSame($expectedCategories, $result['pageCategories']);
    }

    #[Test]
    #[TestDox('Process handles pages with language overlay correctly')]
    public function processHandlesPagesWithLanguageOverlayCorrectly(): void
    {
        $contentObjectConfiguration = [];
        $processorConfiguration = ['field' => 'categories'];
        $processedData = [
            'data' => [
                'uid' => 123,
                '_PAGES_OVERLAY_LANGUAGE' => 2,
            ],
        ];
        $expectedCategories = [['title' => 'Language Category']];

        $this->contentObjectRenderer->data = ['uid' => 123];
        $this->processor->method('getPageCategories')
            ->with(2, 'categories', 123)
            ->willReturn($expectedCategories);

        $result = $this->processor->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        self::assertArrayHasKey('pageCategories', $result);
        self::assertSame($expectedCategories, $result['pageCategories']);
    }

    #[Test]
    #[TestDox('Process includes main category when main_category is set')]
    public function processIncludesMainCategoryWhenMainCategoryIsSet(): void
    {
        $contentObjectConfiguration = [];
        $processorConfiguration = ['field' => 'categories'];
        $processedData = [
            'data' => [
                'uid' => 123,
                'main_category' => 789,
            ],
        ];
        $expectedCategories = [['title' => 'Category 1']];
        $expectedMainCategoryTitle = 'Main Category Title';

        $this->contentObjectRenderer->data = ['uid' => 123];
        $this->processor->method('getPageCategories')
            ->with(0, 'categories', 123)
            ->willReturn($expectedCategories);
        $this->processor->method('getMainCategoryTitle')
            ->with(0, 789)
            ->willReturn($expectedMainCategoryTitle);

        $result = $this->processor->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        self::assertArrayHasKey('mainCategory', $result);
        self::assertSame($expectedMainCategoryTitle, $result['mainCategory']);
    }

    #[Test]
    #[TestDox('Process does not include main category when main_category is not set')]
    public function processDoesNotIncludeMainCategoryWhenMainCategoryIsNotSet(): void
    {
        $contentObjectConfiguration = [];
        $processorConfiguration = ['field' => 'categories'];
        $processedData = [
            'data' => [
                'uid' => 123,
            ],
        ];
        $expectedCategories = [['title' => 'Category 1']];

        $this->contentObjectRenderer->data = ['uid' => 123];
        $this->processor->method('getPageCategories')
            ->with(0, 'categories', 123)
            ->willReturn($expectedCategories);

        $result = $this->processor->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        self::assertArrayNotHasKey('mainCategory', $result);
    }

    #[Test]
    #[TestDox('Process handles empty categories correctly')]
    public function processHandlesEmptyCategoriesCorrectly(): void
    {
        $contentObjectConfiguration = [];
        $processorConfiguration = ['field' => 'categories'];
        $processedData = [
            'data' => [
                'uid' => 123,
            ],
        ];
        $expectedCategories = [];

        $this->contentObjectRenderer->data = ['uid' => 123];
        $this->processor->method('getPageCategories')
            ->with(0, 'categories', 123)
            ->willReturn($expectedCategories);

        $result = $this->processor->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        self::assertArrayHasKey('pageCategories', $result);
        self::assertSame($expectedCategories, $result['pageCategories']);
    }

    #[Test]
    #[TestDox('Process handles zero uid correctly')]
    public function processHandlesZeroUidCorrectly(): void
    {
        $contentObjectConfiguration = [];
        $processorConfiguration = ['field' => 'categories'];
        $processedData = [
            'data' => [
                'uid' => 0,
            ],
        ];
        $expectedCategories = [];

        $this->contentObjectRenderer->data = ['uid' => 0];
        $this->processor->method('getPageCategories')
            ->with(0, 'categories', 0)
            ->willReturn($expectedCategories);

        $result = $this->processor->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        self::assertArrayHasKey('pageCategories', $result);
        self::assertSame($expectedCategories, $result['pageCategories']);
    }

    #[Test]
    #[TestDox('Process preserves existing processed data')]
    public function processPreservesExistingProcessedData(): void
    {
        $contentObjectConfiguration = [];
        $processorConfiguration = ['field' => 'categories'];
        $processedData = [
            'data' => ['uid' => 123],
            'existingKey' => 'existingValue',
            'anotherKey' => ['nested' => 'value'],
        ];
        $expectedCategories = [['title' => 'Category 1']];

        $this->contentObjectRenderer->data = ['uid' => 123];
        $this->processor->method('getPageCategories')
            ->with(0, 'categories', 123)
            ->willReturn($expectedCategories);

        $result = $this->processor->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        self::assertArrayHasKey('existingKey', $result);
        self::assertSame('existingValue', $result['existingKey']);
        self::assertArrayHasKey('anotherKey', $result);
        self::assertSame(['nested' => 'value'], $result['anotherKey']);
        self::assertArrayHasKey('pageCategories', $result);
        self::assertSame($expectedCategories, $result['pageCategories']);
    }
}
