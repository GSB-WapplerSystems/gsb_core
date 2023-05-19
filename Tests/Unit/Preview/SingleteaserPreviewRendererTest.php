<?php

namespace ITZBund\GsbCore\Tests\Unit\Preview;

use Exception;
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
    * @throws Exception
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
            'tx_stage_file'=>1,
            'image'=>1,
            ];
    }
}
