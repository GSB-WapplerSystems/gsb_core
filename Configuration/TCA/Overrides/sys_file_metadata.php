<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Ole Hartwig <o.hartwig@moselwal.de> 2023
 * (c) Matthias Peltzer <matthias.peltzer@digitaspixelpark.com> 2023
 * (c) Christian Rath-Ulrich <christian.rath-ulrich@digitaspixelpark.com> 2023
 * (c) Michael Max Busch <m.busch@moselwal.de> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['sys_file_metadata']['columns']['description']['config']['enableRichtext'] = true;

    $newColumns = [
        'is_accessible' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.is_accessible.label',
            'description' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.is_accessible.description',
            'config' => [
                'renderType' => 'checkboxToggle',
                'type' => 'check',
                'default' => 0,
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_metadata', $newColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_metadata',
        '25',
        'is_accessible',
        'after:caption'
    );
})();
