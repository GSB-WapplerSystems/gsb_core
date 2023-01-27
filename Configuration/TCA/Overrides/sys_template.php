<?php

declare(strict_types=1);

defined('TYPO3') or die();

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'gsb_template',
        'Configuration/TypoScript/',
        'GSB Template'
    );
})();
