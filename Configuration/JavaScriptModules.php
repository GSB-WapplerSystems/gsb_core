<?php
return [
    'dependencies' => ['backend'],
    'tags' => [
        'backend.form',
    ],
    'imports' => [
        '@typo3/rte-ckeditor/ckeditor5.js' => 'EXT:gsb_core/Resources/Public/JavaScript/Override/CKEditor/ckeditor.js',
    ],
];
