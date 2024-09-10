<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\ViewHelpers;

use ITZBund\GsbCore\ViewHelpers\FeatureFlagViewHelper;
use PHPUnit\Framework\Attributes\Test;

class FeatureFlagViewHelperTest extends AbstractViewHelperUnitTestCase
{
    #[Test]
    public function featureFlagViewHelperReturnsStringZeroIfFeatureDoesNotExist()
    {
        $result = FeatureFlagViewHelper::renderStatic(
            ['featureKey' => 'nonexistent'],
            function () {},
            $this->getRenderingContextMock()
        );
        self::assertEquals('0', $result);
    }

    #[Test]
    public function featureFlagViewHelperReturnsStringOneIfFeatureExistsAndIsEnabled()
    {
        $featureKey = 'existingFeature';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['features'][$featureKey] = true;

        $result = FeatureFlagViewHelper::renderStatic(
            ['featureKey' => $featureKey],
            function () {},
            $this->getRenderingContextMock()
        );
        self::assertEquals('1', $result);
    }

    #[Test]
    public function featureFlagViewHelperReturnsStringZeroIfFeatureExistsAndIsDisabled()
    {
        $featureKey = 'existingFeature';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['features'][$featureKey] = false;

        $result = FeatureFlagViewHelper::renderStatic(
            ['featureKey' => $featureKey],
            function () {},
            $this->getRenderingContextMock()
        );
        self::assertEquals('0', $result);
    }
}
