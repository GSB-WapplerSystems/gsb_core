<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['types']['uploads']['columnsOverrides']['media']['config']['allowed'] = 'pdf';
})();
