<?php

namespace ITZBund\GsbTemplate\Tests\Unit\Preview;


use Exception;
use ITZBund\GsbTemplate\Preview\GalleryPreviewRenderer;


class GalleryPreviewRendererTest extends AbstractPreviewRendererTest
{
    protected GalleryPreviewRenderer $galleryPreviewRenderer;



    protected function setUp(): void
    {
        parent::setUp();
        //$this->tester = new UnitTester(new Scenario());
        $this->galleryPreviewRenderer = new GalleryPreviewRenderer();
     }



    /**
     * @throws Exception
     */
    public function testRenderPageModulePreviewContent()
    {
        $item = \Codeception\Stub::make(\TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem::class,
            [
                'getRecord' => $this->getDummyDataArray(),

            ]
        );
        $result = $this->galleryPreviewRenderer->renderPageModulePreviewContent($item);
        $this->assertEquals('<div class="text-left"><p style="font-size: 14px; padding-top: 14px;"><b>Layout: gallery-single</b></p></div>',$result);

    }

    protected function getDummyDataArray(): array
    {
        return [
            "uid" => 827,
            "pid" => 79,
            "CType" => "gallery",
            "header" => "header",
            "bodytext" => "bodytext",
            "gallery_layout"=>"gallery-single",
            "gallery_file"=>1
            ];
    }
}
