<?php
/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Ole Hartwig, Matthias Peltzer, Christian Rath-Ulrich
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
    'tx_stage' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Stage.svg',
    ],
    'tx_singleteaser' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Singleteaser.svg',
    ],
    'tx_banner' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Banner.svg',
    ],
    'tx_slider' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Slider.svg',
    ],
    'tx_video' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Video.svg',
    ],
    'tx_audio' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Audio.svg',
    ],
    'tx_gallery' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Gallery.svg',
    ],
    'tx_container' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Container.svg',
        'spinning' => false,
    ],
    'tx_tabs' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Tabs.svg',
        'spinning' => false,
    ],
    'tx_accordion' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Accordion.svg',
        'spinning' => false,
    ],
    'tx_grid' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Grid.svg',
        'spinning' => false,
    ],
    'tx_frame' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/Frame.svg',
        'spinning' => false,
    ],
    'tx_noframe' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:gsb_core/Resources/Public/Images/Icons/NoFrame.svg',
        'spinning' => false,
    ],
];
