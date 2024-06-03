<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Christian Rath-Ulrich
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\Resources\OnlineMedia\Helpers;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\YouTubeHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OverrideYouTubeHelper extends YouTubeHelper
{
    public function getPreviewImage(File $file): string
    {
        $videoId = $this->getOnlineMediaId($file);
        $temporaryFileName = $this->getTempFolderPath() . 'youtube_' . md5($videoId) . '.jpg';

        file_put_contents($temporaryFileName, file_get_contents(Environment::getProjectPath() . '/vendor/itzbund/gsb-core/Resources/Public/Images/video.png'));
        GeneralUtility::fixPermissions($temporaryFileName);
        return $temporaryFileName;
    }

    /**
     * Get OEmbed data
     *
     * @param string $mediaId
     * @return array|null
     */
    protected function getOEmbedData($mediaId): ?array
    {
        return null;
    }
}
