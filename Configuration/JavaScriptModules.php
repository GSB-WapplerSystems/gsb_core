<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Patrick Schriner
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

return [
    'dependencies' => [
        'backend',
    ],
    'tags' => [
        'backend.form',
        'settings.type',
    ],
    'imports' => [
        '@itzbund/gsb-core/site-sets-type/file.js' => 'EXT:gsb_core/Resources/Public/JavaScript/settings/type/file.js',
        '@itzbund/gsb_public_frontend/interactiveImage.js' => 'EXT:gsb_public_frontend/Resources/Public/JavaScripts/interactiveImage.js',
        '@ckeditor/ckeditor5-language-translations.js' => 'EXT:gsb_core/Resources/Public/CKEditor/JavaScript/plugin/ckeditor5-language-translations.js',
    ],
];
