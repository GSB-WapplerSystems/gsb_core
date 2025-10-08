<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Matthias Peltzer, Christian Rath-Ulrich
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

declare(strict_types=1);

namespace ITZBund\GsbCore\Preview;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Domain\RecordFactory;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Contains a preview rendering for the page module of CType="audio"
 * @internal this is a concrete TYPO3 hook implementation and solely used for EXT:frontend and not part of TYPO3's Core API.
 */
class AudioPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = '';
        $table = $item->getTable();
        $record = $item->getRecord();
        /** @var RecordInterface $recordObj */
        $recordObj = GeneralUtility::makeInstance(RecordFactory::class)->createResolvedRecordFromDatabaseRow($table, $record);
        if ($recordObj->has('CType') && $recordObj->get('CType') === 'audio') {
            if ($recordObj->has('bodytext')) {
                $content .= $this->linkEditContent('<div class="text-left">' . $recordObj->get('bodytext') . '</div>', $record);
            }
            if ($recordObj->has('image') && $recordObj->get('image')) {
                $content .= $this->linkEditContent($this->getThumbCodeUnlinked($recordObj->get('image')), $record);
                $fileReferences = BackendUtility::resolveFileReferences('tt_content', 'image', $record);
                if ($fileReferences !== []) {
                    $content .= $this->linkEditContent('', $record);
                }
            }
        }
        return $content;
    }
}
