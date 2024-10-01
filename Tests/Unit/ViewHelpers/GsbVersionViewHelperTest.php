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
        $versionString = GsbVersionViewHelper::renderStatic([], function () {}, $this->getRenderingContextMock());
        self::assertEquals(' 11', $versionString);
    }

    #[Test]
    public function viewHelperReturnsVersionNumberFromEnvironmentIfSet(): void
    {
        putenv('GSB_VERSION=0815');
        $versionString = GsbVersionViewHelper::renderStatic([], function () {}, $this->getRenderingContextMock());
        self::assertEquals(' 0815', $versionString);
        putenv('GSB_VERSION');
    }
}
