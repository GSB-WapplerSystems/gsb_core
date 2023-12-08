<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Ole Hartwig <o.hartwig@moselwal.de> 2023
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
    $GLOBALS['TCA']['tt_content']['types']['uploads']['columnsOverrides']['media']['config']['allowed'] = 'pdf';
})();
