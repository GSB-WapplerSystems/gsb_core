<?php

namespace ITZBund\GsbCore\Tests\Unit\Utility;

use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use ITZBund\GsbCore\Utility\ExtendSiteUtility;
use PHPUnit\Framework\Attributes\Test;

class ExtendedSiteUtilityTest extends Unit
{
    #[Test]
    #[DataProvider('getTestConfigurations')]
    public function overloadWithLocalizedConfigOverloadsExistingKeysForGivenLanguage($config, $language, $result)
    {
        $localizedConfig = ExtendSiteUtility::overloadWithLocalizedConfig($config, $language);

        self::assertEquals($result, $localizedConfig);
    }

    public function getTestConfigurations(): \Generator
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

    #[Test]
    #[DataProvider('arraysContainingKeysWithToggle')]
    public function getLocalizationToggleFieldsReturnsKeysContainingToggle($config, $expectedResult): void
    {
        $actualResult = ExtendSiteUtility::getLocalizationToggleFields($config);

        self::assertEquals($expectedResult, $actualResult);
    }

    public function arraysContainingKeysWithToggle(): \Generator
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

    #[Test]
    #[DataProvider('configurationWithToggleKeysToCopy')]
    public function copyToggleFieldsToLanguageConfigsCopiesFields($config, $expectedResult): void
    {
        $result = ExtendSiteUtility::copyToggleFieldsToLanguageConfigs($config);

        self::assertEquals($expectedResult, $result);
    }

    public function configurationWithToggleKeysToCopy(): \Generator
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
}
