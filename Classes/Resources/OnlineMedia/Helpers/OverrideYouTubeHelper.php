<?php

namespace ITZBund\GsbCore\Resources\OnlineMedia\Helpers;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\YouTubeHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OverrideYouTubeHelper extends YouTubeHelper
{
    public function getPreviewImage(File $file)
    {
        $videoId = $this->getOnlineMediaId($file);
        $temporaryFileName = $this->getTempFolderPath() . 'youtube_' . md5($videoId) . '.jpg';

        if ($this->isOfflineMode()) {
            file_put_contents($temporaryFileName, file_get_contents(Environment::getProjectPath() . '/vendor/itzbund/gsb-core/Resources/Public/Images/video.png'));
            GeneralUtility::fixPermissions($temporaryFileName);
            return $temporaryFileName;
        }
        return parent::getPreviewImage($file);
    }

    /**
     * Get OEmbed data
     *
     * @param string $mediaId
     *  @return array|null
     */
    protected function getOEmbedData($mediaId)
    {
        if ($this->isOfflineMode()) {
            return null;
        }
        parent::getOEmbedData($mediaId);
    }

    private function isOfflineMode(): bool
    {
        return $GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'];
    }
}
