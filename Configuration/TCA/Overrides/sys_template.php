<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    ExtensionManagementUtility::addStaticFile(
        'gsb_core',
        'Configuration/TypoScript',
        'GSB Distribution Package'
    );
})();
