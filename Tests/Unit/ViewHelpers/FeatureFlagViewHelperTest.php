<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use ITZBund\GsbCore\ViewHelpers\FeatureFlagViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class FeatureFlagViewHelperTest extends UnitTestCase
{
    #[Test]
    #[DataProvider('featureFlagViewHelperDataProvider')]
    #[TestDox('Render method returns string "$expectedResult", when checking for feature "$featureKey" and it $_dataName')]
    public function renderStaticReturnsString(string $featureKey, bool $featureEnabled, string $expectedResult): void
    {
        /*###########
        ## Arrange ##
        ###########*/
        /** Features **/
        $featuresMock = $this->getMockBuilder(Features::class)
            ->getMock();
        $featuresMock
            ->method('isFeatureEnabled')
            ->willReturn($featureEnabled);

        /** RenderingContext **/
        $renderingContextMock = $this->getMockBuilder(RenderingContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $featureFlagViewHelper = new FeatureFlagViewHelper($featuresMock);

        /*#######
        ## Act ##
        #######*/
        $featureFlagViewHelper->setRenderingContext($renderingContextMock);
        $featureFlagViewHelper->initializeArguments();
        $featureFlagViewHelper->setArguments(['featureKey' => $featureKey]);
        $assert = $featureFlagViewHelper->render();

        /*##########
        ## Assert ##
        ##########*/
        self::assertEquals($expectedResult, $assert);
    }

    public static function featureFlagViewHelperDataProvider(): \Generator
    {
        yield 'is disabled' => [
            'existingFeature',
            false,
            '0',
        ];
        yield 'is enabled' => [
            'existingFeature',
            true,
            '1',
        ];
    }
}
