<?php

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-feusermanagement of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2025 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund.
 * Author: Martin Neumann (sitegeist media solutions GmbH)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace ITZBund\GsbCore\FormEngine\FieldControl;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class EditHotspotControl extends AbstractNode
{
    /**
     * @return mixed[]
     */
    public function render(): array
    {
        $result = [];
        $image = $this->getImage();
        if ($image && isset($image['identifier'])) {
            $result = [
                'iconIdentifier' => 'tx_imagemap_hotspot_rounded',
                'title' => 'Edit Hotspot',
                'linkAttributes' => [
                    'class' => 'hotspot',
                    'data-tooltip' => $this->data['databaseRow']['tooltip'],
                    'data-coordinates' => $this->data['databaseRow']['coordinates'],
                    'data-image' => $image['identifier'],
                ],
                'stylesheetFiles' => ['EXT:gsb_core/Resources/Public/Build/StyleSheets/interactiveImageBackend.css'],
                'javaScriptModules' => [JavaScriptModuleInstruction::create('@itzbund/gsb_core/interactiveImage/backend.js')],
            ];
        }
        return $result;
    }

    /**
     * @return mixed[]
     */
    private function getImage(): ?array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_reference');
        $sysFileReference = $queryBuilder
            ->select('*')
            ->from('sys_file_reference')
            ->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($this->data['inlineParentUid'], ParameterType::INTEGER)),
                    $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter('tt_content', ParameterType::STRING))
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($sysFileReference) {
            return BackendUtility::getRecord('sys_file', $sysFileReference['uid_local']);
        }
        return null;
    }
}
