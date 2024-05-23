<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Fixtures;

use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

class DummyViewHelperNode extends ViewHelperNode
{
    public function __construct(ViewHelperInterface $viewHelper)
    {
        $this->uninitializedViewHelper = $viewHelper;
    }
}
