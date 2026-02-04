<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Matthias Peltzer, Christian Rath-Ulrich, Markus Gausepohl
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'tx-stage-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Stage.svg',
    ],
    'tx-singleteaser-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Singleteaser.svg',
    ],
    'tx_imagemap' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/ImageMap.svg',
    ],
    'tx-imagemap-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/ImageMap.svg',
    ],
    'tx-imagemap-hotspot-rounded-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/HotspotRounded.svg',
    ],
    'tx-banner-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Banner.svg',
    ],
    'tx-slider-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Slider.svg',
    ],
    'tx-video-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Video.svg',
    ],
    'tx-audio-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Audio.svg',
    ],
    'tx-gallery-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Gallery.svg',
    ],
    'tx-container-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Container.svg',
        'spinning' => false,
    ],
    'tx-tabs-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Tabs.svg',
        'spinning' => false,
    ],
    'tx-accordion-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Accordion.svg',
        'spinning' => false,
    ],
    'tx-grid-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Grid.svg',
        'spinning' => false,
    ],
    'tx-frame-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/Frame.svg',
        'spinning' => false,
    ],
    'tx-noframe-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/NoFrame.svg',
        'spinning' => false,
    ],
    'tx-include-element-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Icons/IncludeElement.svg',
    ],
];
