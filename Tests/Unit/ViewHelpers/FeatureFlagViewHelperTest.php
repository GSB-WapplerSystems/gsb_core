<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use ITZBund\GsbCore\ViewHelpers\FeatureFlagViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class FeatureFlagViewHelperTest extends UnitTestCase
{
    #[Test]
    #[DataProvider('featureFlagViewHelperDataProvider')]
    #[TestDox('Render method returns string "$expectedResult", when checking for feature "$featureKey" and it $_dataName')]
    public function renderStaticReturnsString(string $featureKey, bool $featureEnabled, bool $featureExists, string $expectedResult)
    {
        /*###########
        ## Arrange ##
        ###########*/
        if ($featureExists) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['features'][$featureKey] = $featureEnabled;
        }

        /** RenderingContext **/
        $renderingContextMock = $this->getMockBuilder(RenderingContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /*#######
        ## Act ##
        #######*/
        $assert = FeatureFlagViewHelper::renderStatic(
            ['featureKey' => $featureKey],
            function () {},
            $renderingContextMock
        );

        /*##########
        ## Assert ##
        ##########*/
        self::assertEquals($expectedResult, $assert);
    }

    public static function featureFlagViewHelperDataProvider(): \Generator
    {
        yield 'exists and is disabled' => [
            'existingFeature',
            false,
            true,
            '0',
        ];

        yield 'exists and is enabled' => [
            'existingFeature',
            true,
            true,
            '1',
        ];

        yield 'does not exist' => [
            'nonExistingFeature',
            false,
            false,
            '0',
        ];
    }
}
