<?php

declare(strict_types=1);

namespace ITZBund\GsbTemplate\Preview;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;

/**
 * Contains a preview rendering for the page module of CType="video"
 * @internal this is a concrete TYPO3 hook implementation and solely used for EXT:frontend and not part of TYPO3's Core API.
 */
class VideoPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = '';
        $row = $item->getRecord();
        if ($row['CType'] === 'video') {
            if ($row['bodytext']) {
                $content .= $this->linkEditContent('<div class="text-left">' . $row['bodytext'] . '</div>', $row);
            }
            if ($row['tx_video_poster_image']) {
                $content .= $this->linkEditContent($this->getThumbCodeUnlinked($row, 'tt_content', 'tx_video_poster_image'), $row);
                $fileReferences = BackendUtility::resolveFileReferences('tt_content', 'tx_video_poster_image', $row);
                if (!empty($fileReferences)) {
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
