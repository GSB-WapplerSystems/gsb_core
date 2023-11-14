<?php

return [
    'dependencies' => ['backend'],
    'tags' => [
        'backend.form',
    ],
    'imports' => [
        '@ckeditor/ckeditor5-language' => 'EXT:gsb_core/Resources/Public/CKEditor/JavaScript/plugin/ckeditor5-language.js',
        '@ckeditor/ckeditor5-language-translation-de.js' => 'EXT:gsb_core/Resources/Public/CKEditor/JavaScript/plugin/ckeditor5-language-translation-de.js',
    ],
];
