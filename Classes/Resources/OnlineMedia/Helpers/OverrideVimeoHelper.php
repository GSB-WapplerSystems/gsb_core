<?php

namespace ITZBund\GsbCore\Resources\OnlineMedia\Helpers;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\VimeoHelper;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\YouTubeHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OverrideVimeoHelper extends VimeoHelper
{
    public function getPreviewImage(File $file)
    {
        $url = Environment::getPublicPath();
        $videoId = $this->getOnlineMediaId($file);
        $temporaryFileName = $this->getTempFolderPath() . 'vimeo_' . md5($videoId) . '.jpg';

        if ($this->isOfflineMode()) {
            file_put_contents($temporaryFileName, GeneralUtility::getUrl(
                $url.'/fileadmin/dummy_video_thumbnail.jpg'
            ));
            GeneralUtility::fixPermissions($temporaryFileName);
            return $temporaryFileName;
        }
        return parent::getPreviewImage($file);
    }

    private function isOfflineMode()
    {
        return $GLOBALS['TYPO3_CONF_VARS']['offlineMode'];
    }
}
