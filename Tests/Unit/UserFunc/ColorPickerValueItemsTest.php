<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\UserFunc;

use ITZBund\GsbCore\UserFunc\ColorPickerValueItems;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ColorPickerValueItemsTest extends UnitTestCase
{
    protected ColorPickerValueItems $colorPickerValueItems;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the language service
        $languageServiceMock = $this->createMock(\TYPO3\CMS\Core\Localization\LanguageService::class);
        $languageServiceMock->method('sL')->willReturnArgument(0);

        $GLOBALS['LANG'] = $languageServiceMock;

        $this->colorPickerValueItems = new ColorPickerValueItems();
    }

    #[Test]
    #[TestDox('ColorPickerValueItems can be instantiated')]
    public function colorPickerValueItemsCanBeInstantiated(): void
    {
        self::assertInstanceOf(ColorPickerValueItems::class, $this->colorPickerValueItems);
    }

    #[Test]
    #[TestDox('GetItems returns consistent results on multiple calls')]
    public function getItemsReturnsConsistentResultsOnMultipleCalls(): void
    {
        $config1 = ['site' => $this->createMock(\TYPO3\CMS\Core\Site\Entity\SiteInterface::class)];
        $config2 = ['site' => $this->createMock(\TYPO3\CMS\Core\Site\Entity\SiteInterface::class)];

        $this->colorPickerValueItems->getItems($config1);
        $this->colorPickerValueItems->getItems($config2);

        self::assertSame($config1['items'], $config2['items']);
    }

    #[Test]
    #[TestDox('GetItems handles site without getConfiguration method')]
    public function getItemsHandlesSiteWithoutGetConfigurationMethod(): void
    {
        $siteMock = $this->createMock(\stdClass::class);
        $config = ['site' => $siteMock];

        $this->colorPickerValueItems->getItems($config);

        self::assertArrayHasKey('items', $config);
        self::assertSame([], $config['items']);
    }
}
