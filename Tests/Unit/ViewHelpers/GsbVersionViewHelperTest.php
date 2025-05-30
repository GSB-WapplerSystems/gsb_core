<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use ITZBund\GsbCore\ViewHelpers\GsbVersionViewHelper;
use PHPUnit\Framework\Attributes\Test;

class GsbVersionViewHelperTest extends AbstractViewHelperUnitTestCase
{
    #[Test]
    public function viewHelperReturnsDefaultVersionNumberIfEnvVariableIsNotSet(): void
    {
        putenv('GSB_VERSION');
        $gsbVersionViewHelper = new GsbVersionViewHelper();
        self::assertEquals($gsbVersionViewHelper->initializeArgumentsAndRender(), '11');
    }

    #[Test]
    public function viewHelperReturnsVersionNumberFromEnvironmentIfSet(): void
    {
        putenv('GSB_VERSION=0815');
        $gsbVersionViewHelper = new GsbVersionViewHelper();
        self::assertEquals('0815', $gsbVersionViewHelper->initializeArgumentsAndRender());
        putenv('GSB_VERSION');
    }
}
