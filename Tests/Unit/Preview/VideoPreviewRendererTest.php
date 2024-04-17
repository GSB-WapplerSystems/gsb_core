<?php

namespace ITZBund\GsbCore\Tests\Unit\Preview;

use Exception;
use ITZBund\GsbCore\Preview\VideoPreviewRenderer;

class VideoPreviewRendererTest extends AbstractPreviewRendererTest
{
    protected VideoPreviewRenderer $videoPreviewRenderer;

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
        $result = $this->videoPreviewRenderer->renderPageModulePreviewContent($item);
        self::assertEquals('<div class="text-left">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et</div>', $result);
        $item = \Codeception\Stub::make(
            VideoPreviewRenderer::class,
            [

            ]
        );
    }

    protected function getDummyDataArray(): array
    {
        return [
            'uid' => 725,
            'pid' => 95,
            't3ver_oid' => 0,
            't3ver_wsid' => 0,
            't3ver_state' => 0,
            't3ver_stage' => 0,
            't3_origuid' => 0,
            'tstamp' => 1678725850,
            'crdate' => 1678272939,
            'hidden' => 0,
            'sorting' => 256,
            'CType' => 'video',
            'header' => 'Header',
            'bodytext' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et',
            'image' => 0,
            'imagewidth' => 0,
            'imageorient' => 0,
            'imagecols' => 1,
            'imageborder' => 0,
            'media' => 0,
            'layout' => 0,
            'deleted' => 0,
            'cols' => 0,
            'records' => '',
            'pages' => '',
            'starttime' => 0,
            'endtime' => 0,
            'colPos' => 1,
            'subheader' => 'Subheader',
            'fe_group' => '',
            'header_link' => '',
            'image_zoom' => 0,
            'header_layout' => '1',
            'list_type' => '',
            'sectionIndex' => 1,
            'linkToTop' => 0,
            'file_collections' => '',
            'filelink_size' => 1,
            'filelink_sorting' => '',
            'target' => '',
            'date' => 0,
            'recursive' => 0,
            'imageheight' => 0,
            'sys_language_uid' => 0,
            'tx_impexp_origuid' => 842,
            'pi_flexform' => null,
            'accessibility_title' => '',
            'accessibility_bypass' => 0,
            'accessibility_bypass_text' => '',
            'l18n_parent' => 0,
            'l18n_diffsource' => '{"CType":"","colPos":"","tx_container_parent":"","header_kicker":"","header":"","header_layout":"","header_position":"","tx_header_style":"","subheader":"","bodytext":"","tx_video_video":"","tx_video_videourl":"","image":"","tx_video_caption":"","layout":"","space_before_class":"","space_after_class":"","sectionIndex":"","sys_language_uid":"","hidden":"","starttime":"","endtime":"","fe_group":"","editlock":"","categories":"","rowDescription":""}',
            'selected_categories' => '0',
            'category_field' => '',
            'categories' => 0,
            'editlock' => 0,
            'rowDescription' => '',
            'table_caption' => null,
            'table_delimiter' => 124,
            'table_enclosure' => 0,
            'table_header_position' => 0,
            'table_tfoot' => 0,
            'bullets_type' => 0,
            'uploads_description' => 1,
            'uploads_type' => 1,
            'assets' => 0,
            'header_position' => '',
            'frame_class' => 'default',
            'space_before_class' => '',
            'space_after_class' => '',
            'l10n_source' => 0,
            'table_class' => '',
            'l10n_state' => null,
            'filelink_sorting_direction' => '',
            'tx_header_inside' => 0,
            'tx_video_caption' => '',
            'tx_video_video' => 't3://file?uid=637',
            'tx_video_videourl' => '',
            'tx_audio_poster' => 0,
            'tx_audio_audio' => null,
            'tx_header_style' => '',
            'tx_container_parent' => 0,
            'container_tab_open' => 1,
            'container_accordion_toggle' => 0,
            'container_accordion_open' => 1,
            'slider' => 0,
            'slider_type' => '',
            'slider_columns' => null,
            'grid_type' => '',
            'grid_columns' => '',
            'grid_bgimage' => 0,
            'columns_grid_col_1' => null,
            'columns_grid_col_2' => null,
            'columns_grid_col_3' => null,
            'columns_grid_col_4' => null,
            'header_kicker' => 'Kicker',
            'tx_video_poster_video' => '',
            'tx_video_mainstage' => 0,
            'image' => 1,
            'grid_parallax' => 0,
            'grid_bottom_image' => null,
            'grid_bgcolor' => null,
            'grid_bgfullsize' => 0,
            'grid_container' => 0,
            'grid_light' => 0,
            'tx_stage_bgcolor' => 0,
            'tx_stage_position' => '',
            'tx_stage_bg' => 0,
            'tx_stage_salutation' => 0,
            'grid_imgbg' => 0,
            'gallery_file' => 0,
            'gallery_bg' => 0,
            'gallery_layout' => '',
            'gallery_textcolor' => 0,
            'tx_link' => '',
            'tx_link_layout' => '',
            'tx_link_text' => '',
            'tx_link_position' => '',
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->videoPreviewRenderer = new VideoPreviewRenderer();
    }
}
