<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace ITZBund\GsbCore\Tests\Unit\Resource\OnlineMedia\Helpers;

use Codeception\Attribute\Incomplete;
use Codeception\Test\Unit;
use ITZBund\GsbCore\Resource\OnlineMedia\Helpers\OverrideYouTubeHelper;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Index\MetaDataRepository;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\YouTubeHelper;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Resource\StorageRepository;

class OverrideYouTubeHelperTest extends Unit
{
    protected OverrideYouTubeHelper $overrideYouTubeHelper;

    protected function _before()
    {
        $metaDataRepository = $this->makeEmpty(MetaDataRepository::class);
        $this->overrideYouTubeHelper = new OverrideYouTubeHelper($metaDataRepository);
    }

    protected function _after()
    {
        unset($this->overrideYouTubeHelper);
    }

    #[Test]
    #[Incomplete('Many external dependencies. This never worked')]
    public function getPreviewImageReturnsCorrectImageInOfflineMode()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'] = true;

        $resourceStorage = $this->makeEmpty(ResourceStorage::class, ['getUid' => 1]);

        $file = new File(['size' => 50, 'uid' => 42], $resourceStorage);

        $previewImage = $this->overrideYouTubeHelper->getPreviewImage($file);

        self::assertMatchesRegularExpression('/^.*YouTube_[a-f0-9]{32}\.jpg$/', $previewImage);
    }

    #[Test]
    #[Incomplete('Many external dependencies. This never worked')]
    public function getPreviewImageReturnsParentResultInOnlineMode()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'] = false;

        $storageRepository = $this->makeEmpty(StorageRepository::class);

        $resourceStorage = $this->makeEmpty(ResourceStorage::class);
        $file = new File(['size' => 50, 'uid' => 42], $resourceStorage);

        $previewImage = $this->overrideYouTubeHelper->getPreviewImage($file);

        self::assertSame((new YouTubeHelper($storageRepository))->getPreviewImage($file), $previewImage);
    }
}
