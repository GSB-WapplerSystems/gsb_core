<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class AbstractViewHelperUnitTestCase extends UnitTestCase
{
    public function getRenderingContextMock(): RenderingContext
    {
        return $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
    }
}
