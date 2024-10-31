<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['types']['uploads']['columnsOverrides']['media']['config']['allowed'] = 'pdf';
})();

if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-4070')) {
    $config = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('gsb_core');
    $additionalAllowedFileExtensions = (string)($config['additionalAllowedFileExtensionsForUploadsElement'] ?? '');
    $additionalAllowedFileExtensionsTrimed = ltrim(trim($additionalAllowedFileExtensions), ',');
    if ($additionalAllowedFileExtensionsTrimed !== '') {
        $GLOBALS['TCA']['tt_content']['types']['uploads']['columnsOverrides']['media']['config']['allowed'] .= ',' . $additionalAllowedFileExtensionsTrimed;
    }
}
