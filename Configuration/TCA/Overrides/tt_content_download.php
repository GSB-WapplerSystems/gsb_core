<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['uploads'] = 'tx_uploads_wcad';

    $tempDownload = [
        'tx_uploads_wcad' =>
            [
                'exclude' => true,
                //todo replace lable @max
                'label' => 'LLL:EXT:frontend/Resources/Private/Language/Database.xlf:tt_content.uploads_wcag',
                'config' => [
                    'type' => 'check',
                    'renderType' => 'checkboxToggle',
                    'default' => 0,
                ],
            ],
    ];

    // Feld der allgemeinen Datensatzbeschreibung hinzufügen - noch keine Ausgabe im Backend!
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempDownload);

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'uploadslayout',
        'tx_uploads_wcad'
    );
})();
