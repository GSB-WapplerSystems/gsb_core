<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace ITZBund\GsbTemplate\Preview;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;

/**
 * Contains a preview rendering for the page module of CType="textpic"
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
                if (!empty($fileReferences)) {
                    $linkedContent = '';
                    $content .= $this->linkEditContent($linkedContent, $row);
                    unset($linkedContent);
                }
            }
        }
        if ($row['gallery_layout']) {
            $content .= $this->linkEditContent('<div class="text-left"><p style="font-size: 14px; padding-top: 14px;"><b>Layout: ' . $row['gallery_layout'] . '</b></p></div>', $row);
        }
        return $content;
    }
}
