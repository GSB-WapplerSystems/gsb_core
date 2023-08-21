<?php
return [
    'dependencies' => ['backend'],
    'tags' => [
        'backend.form',
    ],
    'imports' => [
        '@typo3/rte-ckeditor/' => 'EXT:gsb_core/Resources/Public/CKEditor/JavaScript/',
        '@typo3/ckeditor5-bundle.js' => 'EXT:gsb_core/Resources/Public/CKEditor/Contrib/ckeditor5-bundle.js',
        '@typo3/ckeditor5-inspector.js' => 'EXT:gsb_core/Resources/Public/CKEditor/Contrib/ckeditor5-inspector.js',
        '@typo3/rte-ckeditor/ckeditor5.js' => 'EXT:gsb_core/Resources/Public/CKEditor/JavaScript/ckeditor5.js',
    ],
];
