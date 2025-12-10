<?php

// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use ITZBund\GsbCore\ViewHelpers\GsbVersionViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;

class GsbVersionViewHelperTest extends UnitTestCase
{
    #[Test]
    #[DataProvider('gsbVersionViewHelperDataProvider')]
    #[TestDox('Render method returns string " $expectedResult", when $_dataName')]
    public function renderReturnsVersionNumberFromEnvironment(bool $customEnvValue, string $expectedResult): void
    {
        /*###########
        ## Arrange ##
        ###########*/
        $gsbVersionViewHelper = new GsbVersionViewHelper();
        $renderingContext = new RenderingContext();
        $gsbVersionViewHelper->setRenderingContext($renderingContext);

        if ($customEnvValue) {
            putenv('GSB_VERSION=' . $expectedResult);
        }

        /*#######
        ## Act ##
        #######*/
        $assert = $gsbVersionViewHelper->render();

        /*##########
        ## Assert ##
        ##########*/
        self::assertEquals($expectedResult, $assert);

        putenv('GSB_VERSION');
    }

    public static function gsbVersionViewHelperDataProvider(): \Generator
    {
        yield 'using default environment variable' => [
            false,
            '11',
        ];

        yield 'overwriting default environment variable with "0815"' => [
            true,
            '0815',
        ];
    }
}
