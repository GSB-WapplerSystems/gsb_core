<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Resource\OnlineMedia\Helpers;

use Codeception\Attribute\Incomplete;
use Codeception\Test\Unit;
use ITZBund\GsbCore\Resource\OnlineMedia\Helpers\OverrideVimeoHelper;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Index\MetaDataRepository;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\VimeoHelper;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Resource\StorageRepository;

class OverrideVimeoHelperTest extends Unit
{
    protected OverrideVimeoHelper $overrideVimeoHelper;

    protected function _before()
    {
        $metaDataRepository = $this->makeEmpty(MetaDataRepository::class);
        $this->overrideVimeoHelper = new OverrideVimeoHelper($metaDataRepository);
    }

    protected function _after()
    {
        unset($this->overrideVimeoHelper);
    }

    #[Test]
    #[Incomplete('Many external dependencies. This never worked')]
    public function getPreviewImageReturnsCorrectImageInOfflineMode()
    {
        // Set the offline mode to true
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'] = true;

        // Create a mock StorageRepository and ResourceStorage
        $resourceStorage = $this->makeEmpty(ResourceStorage::class, ['getUid' => 1]);

        $file = new File(['size' => 50, 'uid' => 42], $resourceStorage);

        // Call the method and retrieve the temporary file name
        $previewImage = $this->overrideVimeoHelper->getPreviewImage($file);

        // Check if the temporary file name has the expected format
        self::assertMatchesRegularExpression('/^.*vimeo_[a-f0-9]{32}\.jpg$/', $previewImage);
    }

    #[Test]
    #[Incomplete('Many external dependencies. This never worked')]
    public function getPreviewImageReturnsParentResultInOnlineMode()
    {
        // Set the offline mode to false
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'] = false;

        // Create a mock StorageRepository and ResourceStorage
        $storageRepository = $this->makeEmpty(StorageRepository::class);
        $resourceStorage = $this->makeEmpty(ResourceStorage::class, ['getUid' => 1]);

        $file = new File(['size' => 50, 'uid' => 42], $resourceStorage);

        // Call the method and retrieve the temporary file name
        $previewImage = $this->overrideVimeoHelper->getPreviewImage($file);

        // Check if the result is the same as the parent class method
        self::assertSame((new VimeoHelper($storageRepository))->getPreviewImage($file), $previewImage);
    }
}
