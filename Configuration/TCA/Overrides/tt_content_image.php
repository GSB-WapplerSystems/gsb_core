<?php
declare(strict_types=1);

defined('TYPO3') || die();

(static function (): void {

    $palettes['image_override'] = [
        'label' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.palettes.image_override.header',
        'showitem' => implode(
            ',',
            [
                'image',
            ],
        ),
    ];

    $GLOBALS['TCA']['tt_content']['palettes'] += $palettes;

    $showitem = $GLOBALS['TCA']['tt_content']['types']['image']['showitem'] ?? '';

    $showitem = str_replace(
        'image,',
        '--palette--;LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.palettes.image_override.header;image_override,',
        $showitem
    );

    $GLOBALS['TCA']['tt_content']['types']['image']['showitem'] = $showitem;

})();
