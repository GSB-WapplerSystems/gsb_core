<?php

declare(strict_types=1);
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    /*
     * Define TypoScript as content rendering template
     */

    $GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'gsb_template/Configuration/TypoScript/';
    $GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'gsb_template/Configuration/TypoScript/ContentElement/';

    /*
     * UserTS
     */

    ExtensionManagementUtility::addUserTSConfig(
        "@include 'EXT:gsb_template/Configuration/TsConfig/userTSConfig.tsconfig'"
    );

    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:gsb_template/Configuration/RTE/12-Default.yaml';
})();
