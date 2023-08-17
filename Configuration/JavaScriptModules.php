<?php
return [
    'dependencies' => ['backend'],
    'tags' => [
        'backend.form',
    ],
    'imports' => [
        '@gsb/sitepackage/gsb-adjustment' => 'EXT:gsb_sitepackage/Resources/Public/CKEditor/gsb-ckeditor-adjustment.js',
        '@gsb/sitepackage/gsb-add-abbr-tag' => 'EXT:gsb_sitepackage/Resources/Public/CKEditor/gsb-add-abbr-tag/GSBAddAbreviationTag.js',
        '@gsb/sitepackage/gsb-add-lang-tag' => 'EXT:gsb_sitepackage/Resources/Public/CKEditor/gsb-add-lang-tag/GSBAddLangTag.js'
    ],
];
