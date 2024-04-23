<?php

declare(strict_types=1);

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['types']['uploads']['columnsOverrides']['media']['config']['allowed'] = 'pdf';
})();
