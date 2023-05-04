<?php

declare(strict_types=1);

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['sys_file_metadata']['columns']['description']['config']['enableRichtext'] = true;
})();
