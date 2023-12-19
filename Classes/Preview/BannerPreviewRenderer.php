<?php

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Kai Ole Hartwig <o.hartwig@moselwal.de> 2023
 * (c) Matthias Peltzer <matthias.peltzer@digitaspixelpark.com> 2023
 * (c) Christian Rath-Ulrich <christian.rath-ulrich@digitaspixelpark.com> 2023
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
 * Contains a preview rendering for the page module of CType="banner"
 * @internal this is a concrete TYPO3 hook implementation and solely used for EXT:frontend and not part of TYPO3's Core API.
 */
class BannerPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = '';
        $row = $item->getRecord();
        if ($row['CType'] === 'gsb_banner') {
            if ($row['tx_link_text']) {
                $content .= $this->linkEditContent('<div class="text-left">' . $row['tx_link_text'] . '</div>', $row);
            }
            if ($row['image']) {
                $content .= $this->linkEditContent($this->getThumbCodeUnlinked($row, 'tt_content', 'image'), $row);
                $fileReferences = BackendUtility::resolveFileReferences('tt_content', 'image', $row);
                if ($fileReferences !== []) {
                    // @codeCoverageIgnoreStart
                    $linkedContent = '';
                    $content .= $this->linkEditContent($linkedContent, $row);
                    unset($linkedContent);
                    // @codeCoverageIgnoreEnd
                }
            }
        }
        return $content;
    }
}
