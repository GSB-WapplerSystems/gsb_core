<?php

declare(strict_types=1);

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Matthias Peltzer, Christian Rath-Ulrich
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\Preview;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;

/**
 * Contains a preview rendering for the page module of CType="gallery"
 * @internal this is a concrete TYPO3 hook implementation and solely used for EXT:frontend and not part of TYPO3's Core API.
 */
class GalleryPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = '';
        $row = $item->getRecord();

        if ($row['CType'] === 'gallery') {
            if ($row['gallery_file']) {
                $content .= $this->linkEditContent($this->getThumbCodeUnlinked($row, 'tt_content', 'gallery_file'), $row);
                $fileReferences = BackendUtility::resolveFileReferences('tt_content', 'gallery_file', $row);
                if ($fileReferences !== []) {
                    // @codeCoverageIgnoreStart
                    $linkedContent = '';
                    $content .= $this->linkEditContent($linkedContent, $row);
                    unset($linkedContent);
                    // @codeCoverageIgnoreEnd
                }
            }
        }

        if ($row['gallery_layout']) {
            $content .= $this->linkEditContent('<div class="text-left"><p style="font-size: 14px; padding-top: 14px;"><b>Layout: ' . $row['gallery_layout'] . '</b></p></div>', $row);
        }
        return $content;
    }
}
