<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\Utility;

use ITZBund\GsbCore\Utility\ExtendSiteUtility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ExtendedSiteUtilityTest extends UnitTestCase
{
    /**
     * @param array<string, string|array<string, string|int>> $config
     * @param int $language
     * @param array<string, string|array<string, string|int>> $expected
     */
    #[Test]
    #[DataProvider('getTestConfigurations')]
    public function overloadWithLocalizedConfigOverloadsExistingKeysForGivenLanguage(array $config, int $language, array $expected): void
    {
        $subject = new \ReflectionClass(ExtendSiteUtility::class);
        $method = $subject->getMethod('overloadWithLocalizedConfig');
        $method->setAccessible(true);
        $result = $method->invokeArgs(new ExtendSiteUtility(), [$config, $language]);
        self::assertEquals($expected, $result);
    }

    public static function getTestConfigurations(): \Generator
    {
        yield 'Does not overwrite if language does not match' => [
            [
                'key_to_overwrite' => 'initial value',
                'languages' => [
                    [
                        'languageId' => 2,
                        'key_to_overwrite' => 'overridden value',
                    ],
                ],
            ],
            1,
            [
                'key_to_overwrite' => 'initial value',
                'languages' => [
                    [
                        'languageId' => 2,
                        'key_to_overwrite' => 'overridden value',
                    ],
                ],
            ],
        ];
        yield 'Does overwrite if language matches' => [
            [
                'key_to_overwrite' => 'initial value',
                'languages' => [
                    [
                        'languageId' => 2,
                        'key_to_overwrite' => 'overridden value',
                    ],
                ],
            ],
            2,
            [
                'key_to_overwrite' => 'overridden value',
                'languages' => [
                    [
                        'languageId' => 2,
                        'key_to_overwrite' => 'overridden value',
                    ],
                ],
            ],
        ];
        yield 'Does not add language keys to global configuration' => [
            [
                'some_key' => 'initial value',
                'languages' => [
                    [
                        'languageId' => 2,
                        'some_key' => 'overridden value',
                        'no_global_equivalent' => 'some value',
                    ],
                ],
            ],
            2,
            [
                'some_key' => 'overridden value',
                'languages' => [
                    [
                        'languageId' => 2,
                        'some_key' => 'overridden value',
                        'no_global_equivalent' => 'some value',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string[] $config
     * @param string[] $expectedResult
     */
    #[Test]
    #[DataProvider('arraysContainingKeysWithToggle')]
    public function getLocalizationToggleFieldsReturnsKeysContainingToggle(array $config, array $expectedResult): void
    {
        $actualResult = (new ExtendSiteUtility())->getLocalizationToggleFields($config);

        self::assertEquals($expectedResult, $actualResult);
    }

    public static function arraysContainingKeysWithToggle(): \Generator
    {
        yield 'Returns empty array when no key contains "toggle"' => [
            [
                'some_key' => 'initial value',
                'another_key' => 'another value',
                'and_another_one' => 'another value',
            ],
            [],
        ];
        yield 'Returns all keys containing toggle' => [
            [
                'some_key' => 'initial value',
                'key_with_toggle' => 'this is needed',
                'another_key' => 'another value',
            ],
            [
                'key_with_toggle',
            ],
        ];
    }

    /**
     * @param array<string, string|array<string, string|int>> $config
     * @param array<string, string|array<string, string|int>> $expectedResult
     */
    #[Test]
    #[DataProvider('configurationWithToggleKeysToCopy')]
    public function copyToggleFieldsToLanguageConfigsCopiesFields(array $config, array $expectedResult): void
    {
        $result = (new ExtendSiteUtility())->copyToggleFieldsToLanguageConfigs($config);

        self::assertEquals($expectedResult, $result);
    }

    public static function configurationWithToggleKeysToCopy(): \Generator
    {
        yield 'Copies toggle key to languages' => [
            [
                'some_key' => 'some value',
                'toggle_key' => 'toggle_key',
                'languages' => [
                    [
                        'languageId' => 1,
                    ],
                ],
            ],
            [
                'some_key' => 'some value',
                'toggle_key' => 'toggle_key',
                'languages' => [
                    [
                        'languageId' => 1,
                        'toggle_key' => 'toggle_key',
                    ],
                ],
            ],
        ];
        yield 'Overwrites existing values for languages' => [
            [
                'toggle_key_to_overwrite' => 'overridden value',
                'languages' => [
                    [
                        'languageId' => 1,
                    ],
                    [
                        'languageId' => 2,
                        'toggle_key_to_overwrite' => 'initial value',
                    ],
                ],
            ],
            [
                'toggle_key_to_overwrite' => 'overridden value',
                'languages' => [
                    [
                        'languageId' => 1,
                        'toggle_key_to_overwrite' => 'overridden value',
                    ],
                    [
                        'languageId' => 2,
                        'toggle_key_to_overwrite' => 'overridden value',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, string|array<string, string|int>> $config
     * @param array<string, array<int, array<string, int>>> $control
     * @param array<string, string|array<string, string|int>> $expectedResult
     */
    #[Test]
    #[DataProvider('nullableFieldsControlData')]
    public function excludeNullableFieldsRemovesValuesThatShouldGetTheFallbackValue($config, $control, $expectedResult): void
    {
        $result = (new ExtendSiteUtility())->removeSelectedNullableFields($config, $control);

        self::assertEquals($expectedResult, $result);
    }

    public static function nullableFieldsControlData(): \Generator
    {
        yield 'nullable fields doesnt change the array if control data array is empty' => [
            [
                'some_key' => 'some value',
                'some_default_key' => 'some default value',
                'languages' => [
                    [
                        'languageId' => 1,
                        'some_default_key' => '',
                    ],
                ],
            ],
            [],
            [
                'some_key' => 'some value',
                'some_default_key' => 'some default value',
                'languages' => [
                    [
                        'languageId' => 1,
                        'some_default_key' => '',
                    ],
                ],
            ],
        ];
        yield 'nullable fields removes field if control data array has field set with value 0' => [
            [
                'some_key' => 'some value',
                'some_default_key' => 'some default value',
                'languages' => [
                    [
                        'languageId' => 1,
                        'some_default_key' => '',
                    ],
                ],
            ],
            [
                'site_language' => [
                    0 => [
                        'some_default_key' => 0,
                    ],
                ],
            ],
            [
                'some_key' => 'some value',
                'some_default_key' => 'some default value',
                'languages' => [
                    [
                        'languageId' => 1,
                    ],
                ],
            ],
        ];
        yield 'nullable fields does not remove field if control data array has field set with value 1' => [
            [
                'some_key' => 'some value',
                'some_default_key' => 'some default value',
                'languages' => [
                    [
                        'languageId' => 1,
                        'some_default_key' => '',
                    ],
                ],
            ],
            [
                'site_language' => [
                    0 => [
                        'some_default_key' => 1,
                    ],
                ],
            ],
            [
                'some_key' => 'some value',
                'some_default_key' => 'some default value',
                'languages' => [
                    [
                        'languageId' => 1,
                        'some_default_key' => '',
                    ],
                ],
            ],
        ];
    }
    /**
     * @param array<string, string|array<string, string|int>> $config
     * @param array<string, string|array<string, string|int>> $settings
     * @param int $language
     * @param array<string, string|array<string, string|int>> $expected
     */
    #[Test]
    #[DataProvider('provideOverrideSettingsWithLocalizedConfig')]
    public function testOverrideSettingsWithLocalizedConfig(array $config, array $settings, int $language, array $expected): void
    {
        $subject = new \ReflectionClass(ExtendSiteUtility::class);
        $method = $subject->getMethod('overrideSettingsWithLocalizedConfig');
        $method->setAccessible(true);
        $result = $method->invokeArgs(new ExtendSiteUtility(), [$config, $settings, $language]);
        self::assertEquals($expected, $result);
    }

    public static function provideOverrideSettingsWithLocalizedConfig(): \Generator
    {
        yield 'Does overwrite if language matches' => [
            [
                'logos.gsb-initiative-text' => 'initial value',
            ],
            [
                'languages' => [
                    [
                        'languageId' => 1,
                        'initiative-text' => 'overridden value',
                    ],
                ],
            ],
            1,
            [
                'logos' => [
                    'gsb-initiative-text' => 'overridden value',
                ],
            ],
        ];
        yield 'Does not overwrite if language id does not matche' => [
            [
                'logos.gsb-initiative-text' => 'initial value',
            ],
            [
                'languages' => [
                    [
                        'languageId' => 1,
                        'initiative-text' => 'overridden value',
                    ],
                ],
            ],
            1101101,
            [
                'logos' => [
                    'gsb-initiative-text' => 'initial value',
                ],
            ],
        ];
    }
}
