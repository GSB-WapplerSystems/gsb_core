<?php

namespace ITZBund\GsbCore\Tests\Unit\Resources\OnlineMedia\Helpers;

use Codeception\Test\Unit;
use ITZBund\GsbCore\Resources\OnlineMedia\Helpers\OverrideYouTubeHelper;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Index\MetaDataRepository;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\YouTubeHelper;

class OverrideYouTubeHelperTest extends Unit
{
    /**
    @var OverrideYouTubeHelper
     */
    protected $overrideYouTubeHelper;


    protected function _before()
    {
        $metaDataRepository = $this->makeEmpty(MetaDataRepository::class);
        $this->overrideYouTubeHelper = new OverrideYouTubeHelper($metaDataRepository);
    }

    protected function _after()
    {
        unset($this->overrideYouTubeHelper);
    }

    public function getPreviewImageReturnsCorrectImageInOfflineMode()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'] = true;

        $resourceStorage = $this->makeEmpty(ResourceStorage::class, ['getUid' => 1]);


        $file = $this->make(File::class, [
            'getProperty' => '123456',
            'getStorage' => $resourceStorage
        ]);


        $previewImage = $this->overrideYouTubeHelper->getPreviewImage($file);


        $this->assertMatchesRegularExpression('/^.*YouTube_[a-f0-9]{32}\.jpg$/', $previewImage);
    }

    public function getPreviewImageReturnsParentResultInOnlineMode()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'] = false;


        $storageRepository = $this->makeEmpty(StorageRepository::class);
        $resourceStorage = $this->makeEmpty(ResourceStorage::class, ['getUid' => 1]);


        $file = $this->make(File::class, [
            'getProperty' => '123456',
            'getStorage' => $resourceStorage
        ]);


        $previewImage = $this->overrideYouTubeHelper->getPreviewImage($file);


        $this->assertSame((new YouTubeHelper($storageRepository))->getPreviewImage($file), $previewImage);
    }
}
