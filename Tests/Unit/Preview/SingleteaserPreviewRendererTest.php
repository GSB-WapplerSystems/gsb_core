<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Preview;

use ITZBund\GsbCore\Preview\SingleteaserPreviewRenderer;

class SingleteaserPreviewRendererTest extends AbstractPreviewRendererTest
{
    protected SingleteaserPreviewRenderer $singleteaserPreviewRenderer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->singleteaserPreviewRenderer = new SingleteaserPreviewRenderer();
    }

    /**
    * @throws \Exception
    */
    public function testRenderPageModulePreviewContent()
    {
        $item = \Codeception\Stub::make(
            \TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem::class,
            [
                'getRecord' => $this->getDummyDataArray(),

            ]
        );
        $result = $this->singleteaserPreviewRenderer->renderPageModulePreviewContent($item);
        self::assertEquals('<div class="text-left">bodytext</div>', $result);
    }

    protected function getDummyDataArray(): array
    {
        return [
            'uid' => 827,
            'pid' => 79,
            'CType' => 'gsb_singleteaser',
            'header' => 'header',
            'bodytext' => 'bodytext',
            'image' => 1,
        ];
    }
}
