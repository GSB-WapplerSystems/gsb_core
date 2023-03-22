<?php

namespace ITZBund\GsbTemplate\Tests\Unit\Preview;

use Exception;
use ITZBund\GsbTemplate\Preview\StagePreviewRenderer;

class StagePreviewRendererTest extends AbstractPreviewRendererTest
{
    protected StagePreviewRenderer $stagePreviewRenderer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stagePreviewRenderer = new StagePreviewRenderer();
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
        $result = $this->stagePreviewRenderer->renderPageModulePreviewContent($item);
        self::assertEquals('<div class="text-left">bodytext</div>', $result);
    }

    protected function getDummyDataArray(): array
    {
        return [
            'uid' => 827,
            'pid' => 79,
            'CType' => 'stage',
            'header' => 'header',
            'bodytext' => 'bodytext',
            'tx_stage_file'=>1,
            ];
    }
}
