<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package itzbund/gsb-container.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die('Access denied.');

(static function (): void {
    /**
     * Register container
     */
    GeneralUtility::makeInstance(Registry::class)->configureContainer(
        (
            new ContainerConfiguration(
                'ce_container',
                'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:container.title',
                'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:container.description',
                [
                    [
                        [
                            'name' => 'LLL:EXT:gsb_template/Resources/Private/Language/locallang_db.xlf:content',
                            'colPos' => 101
                        ]
                    ]
                ]
            )
        )
        ->setIcon('tx_container')
        ->setBackendTemplate('EXT:gsb_template/Resources/Private/Backend/Templates/Container.html')
        ->setSaveAndCloseInNewContentElementWizard(true)
    );

    // override default settings
    $GLOBALS['TCA']['tt_content']['types']['ce_container']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,header_kicker,header,
        --palette--;;header_config,subheader,
    --div--;Container,grid_container,grid_bgcolor,grid_imgbg,grid_bgimage,grid_bgfullsize,grid_parallax,grid_bottom_image,grid_light,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
    --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,categories,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended;'
    ;
})();
