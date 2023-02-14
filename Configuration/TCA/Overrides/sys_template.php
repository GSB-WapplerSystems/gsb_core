<?php

/*
 * This file is part of the package itzbund/gsb-template.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

/***************
 * TypoScript: Full Package
 * This includes the full setup including all configurations
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'gsb_template',
    'Configuration/TypoScript',
    'GSB Template: Bootstrap Package'
);
