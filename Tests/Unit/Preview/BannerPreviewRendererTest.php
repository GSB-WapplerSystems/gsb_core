<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Preview;

use ITZBund\GsbCore\Preview\BannerPreviewRenderer;

class BannerPreviewRendererTest extends AbstractPreviewRendererTest
{
    protected BannerPreviewRenderer $BannerPreviewRenderer;

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
        $result = $this->BannerPreviewRenderer->renderPageModulePreviewContent($item);
        self::assertEquals('<div class="text-left">link text</div>', $result);
    }

    protected function getDummyDataArray(): array
    {
        return [
            'uid' => 827,
            'pid' => 79,
            'CType' => 'gsb_banner',
            'header' => 'header',
            'bodytext' => 'bodytext',
            'tx_link_text' => 'link text',
            'image' => 1,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->BannerPreviewRenderer = new BannerPreviewRenderer();
    }
}
