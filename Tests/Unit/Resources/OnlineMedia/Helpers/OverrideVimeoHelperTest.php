<?php

namespace ITZBund\GsbCore\Tests\Unit\Resources\OnlineMedia\Helpers;

use Codeception\Test\Unit;
use ITZBund\GsbCore\Resources\OnlineMedia\Helpers\OverrideVimeoHelper;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Index\MetaDataRepository;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\VimeoHelper;

class OverrideVimeoHelperTest extends Unit
{
    /**
    @var OverrideVimeoHelper
     */
    protected $overrideVimeoHelper;

    protected function _before()
    {
        $metaDataRepository = $this->makeEmpty(MetaDataRepository::class);
        $this->overrideVimeoHelper = new OverrideVimeoHelper($metaDataRepository);
    }

    protected function _after()
    {
        unset($this->overrideVimeoHelper);
    }

    public function getPreviewImageReturnsCorrectImageInOfflineMode()
    {
        // Set the offline mode to true
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'] = true;

        // Create a mock StorageRepository and ResourceStorage
        $storageRepository = $this->makeEmpty(StorageRepository::class);
        $resourceStorage = $this->makeEmpty(ResourceStorage::class, ['getUid' => 1]);

        // Create a mock File object with a specific online media ID and Storage
        $file = $this->make(File::class, [
            'getProperty' => '123456',
            'getStorage' => $resourceStorage,
        ]);

        // Call the method and retrieve the temporary file name
        $previewImage = $this->overrideVimeoHelper->getPreviewImage($file);

        // Check if the temporary file name has the expected format
        self::assertMatchesRegularExpression('/^.*vimeo_[a-f0-9]{32}\.jpg$/', $previewImage);
    }

    public function getPreviewImageReturnsParentResultInOnlineMode()
    {
        // Set the offline mode to false
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'] = false;

        // Create a mock StorageRepository and ResourceStorage
        $storageRepository = $this->makeEmpty(StorageRepository::class);
        $resourceStorage = $this->makeEmpty(ResourceStorage::class, ['getUid' => 1]);

        // Create a mock File object with a specific online media ID and Storage
        $file = $this->make(File::class, [
            'getProperty' => '123456',
            'getStorage' => $resourceStorage,
        ]);

        // Call the method and retrieve the temporary file name
        $previewImage = $this->overrideVimeoHelper->getPreviewImage($file);

        // Check if the result is the same as the parent class method
        self::assertSame((new VimeoHelper($storageRepository))->getPreviewImage($file), $previewImage);
    }
}
