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

namespace ITZBund\GsbCore\Resource\OnlineMedia\Helpers;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\VimeoHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OverrideVimeoHelper extends VimeoHelper
{
    public function getPreviewImage(File $file): string
    {
        $videoId = $this->getOnlineMediaId($file);
        $temporaryFileName = $this->getTempFolderPath() . 'vimeo_' . md5($videoId) . '.png';
        if (!file_exists($temporaryFileName)) {
            $source = GeneralUtility::getFileAbsFileName('EXT:gsb_core/Resources/Public/Images/video.png');
            if ($source == '') {
                return '';
            }
            copy($source, $temporaryFileName);
            GeneralUtility::fixPermissions($temporaryFileName);
        }
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
