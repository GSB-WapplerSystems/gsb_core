<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\UserFunc;

use ITZBund\GsbCore\UserFunc\ColorPickerValueItems;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ColorPickerValueItemsTest extends UnitTestCase
{
    protected ?ColorPickerValueItems $subject = null;

    public function setUp(): void
    {
        parent::setUp();

        $colorPickerValueItemsMock = $this->getMockBuilder(ColorPickerValueItems::class)->onlyMethods(['getLanguageService'])->getMock();
        $languageServiceMock = $this->getMockBuilder(LanguageService::class)->disableOriginalConstructor()->getMock();

        $languageServiceMock->method('sL')->withAnyParameters()->willReturn('TRANSLATED_DUMMY_STRING');
        $colorPickerValueItemsMock->method('getLanguageService')->willReturn($languageServiceMock);

        $this->subject = $colorPickerValueItemsMock;
    }

    #[Test]
    public function getItemsDoesNotAddItemsToConfigIfSiteDoesNotExist()
    {
        $config = [
            'site' => new \stdClass(),
        ];

        $this->subject->getItems($config);

        self::assertEquals(['site' => $config['site'], 'items' => []], $config);
    }

    #[Test]
    #[DataProvider('siteConfigurationDataForColorPickerValueItems')]
    public function getItemsCorrectlyBuildsItemsArrayFromConfigurationWithValuesAsLabels($configuration, $expectedValues)
    {
        $site = $this->getMockBuilder(Site::class)->disableOriginalConstructor()->getMock();
        $site->method('getConfiguration')->willReturn($configuration);

        $config = [
            'site' => $site,
        ];

        $this->subject->getItems($config);

        self::assertEquals(
            [
                'site' => $config['site'],
                'items' => $expectedValues,
            ],
            $config,
        );
    }

    public static function siteConfigurationDataForColorPickerValueItems(): \Generator
    {
        yield 'set values als labels when no label configuration exists' => [
            [
                'non-relevant-var-1' => 'dummyvalue',
                'non-relevant-var-2' => 'another-dummyvalue',
                'color_1' => '#123',
                'color_2' => '#456',
                'color_a' => 'invalid',
                'color_f1' => 'invalid-too',
                'color_1f' => 'also-invalid',
            ],
            [
                ['TRANSLATED_DUMMY_STRING', ''],
                ['#123', 'color_1'],
                ['#456', 'color_2'],
            ],
        ];

        yield 'set values with corresponding labels when label configuration exists' => [
            [
                'non-relevant-var-1' => 'dummyvalue',
                'non-relevant-var-2' => 'another-dummyvalue',
                'color_1' => '#123',
                'color_2' => '#456',
                'color_3' => '#789',
                'label_color_2' => 'color 2 label',
                'label_color_3' => 'color 3 label',
            ],
            [
                ['TRANSLATED_DUMMY_STRING', ''],
                ['#123', 'color_1'],
                ['color 2 label', 'color_2'],
                ['color 3 label', 'color_3'],
            ],
        ];

    }
}
