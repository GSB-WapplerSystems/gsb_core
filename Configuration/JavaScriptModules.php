<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Kai Ole Hartwig <o.hartwig@moselwal.de> 2023
 * (c) Patrick Schriner <patrick.schriner@diemedialen.de> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
