<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die('Access denied.');

(function () {
    $extensionKey = 'gsb_template';

    /** @var ExtensionConfiguration $extConfig */
    $extConfig = GeneralUtility::makeInstance(
        ExtensionConfiguration::class
    )->get($extensionKey, 'config');

    // automatically add TypoScript, can be disabled in the extension configuration (BE)
    if ($extConfig['typoScript'] === '1') {
        // Add Constants
        ExtensionManagementUtility::addTypoScriptConstants(
            "@import 'EXT:" . $extensionKey . "/Configuration/TypoScript/constants.typoscript'"
        );

        // Add Setup
        ExtensionManagementUtility::addTypoScriptSetup(
            "@import 'EXT:" . $extensionKey . "/Configuration/TypoScript/setup.typoscript'"
        );
    }
})();
