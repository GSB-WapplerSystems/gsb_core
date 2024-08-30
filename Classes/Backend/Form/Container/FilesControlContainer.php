<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
  * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
  *
  * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
  * BMI/ITZBund. Author: Luchezar Chakardzhiyan
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 3
  * of the License, or any later version.
  *
  * For the full copyright and license information, please read the
  * LICENSE file that was distributed with this source code.
  */

namespace ITZBund\GsbCore\Backend\Form\Container;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FilesControlContainer extends \TYPO3\CMS\Backend\Form\Container\FilesControlContainer
{
    /**
     * @return array<mixed>
     */
    public function render(): array
    {
        if (array_key_exists('fieldInformation', $this->data['parameterArray']['fieldConf']['config'])) {
            if (!array_key_exists('container', $this->data['processedTca']['ctrl'])) {
                $this->data['processedTca']['ctrl']['container'] = [];
            }

            if (!array_key_exists('file', $this->data['processedTca']['ctrl']['container'])) {
                $this->data['processedTca']['ctrl']['container']['file'] = [];
            }

            if (!array_key_exists('fieldInformation', $this->data['processedTca']['ctrl']['container']['file'])) {
                $this->data['processedTca']['ctrl']['container']['file']['fieldInformation'] = [];
            }

            $this->data['processedTca']['ctrl']['container']['file']['fieldInformation'] = array_merge(
                $this->data['processedTca']['ctrl']['container']['file']['fieldInformation'],
                $this->data['parameterArray']['fieldConf']['config']['fieldInformation']
            );
        }

        if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-3435')) {
            $this->addVideoDescriptionField();
        }

        return parent::render();
    }

    public function addVideoDescriptionField(): void
    {
        if (! str_ends_with($this->data['parameterArray']['itemFormElName'] ?? '', '[tx_video_video]')) {
            return;
        }

        $config = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('gsb_core');
        $lll = (GeneralUtility::makeInstance(LanguageServiceFactory::class))->createFromUserPreferences(
            $GLOBALS['BE_USER']
        );

        $desc = 'LLL:EXT:gsb_core/Resources/Private/Language/locallang.xlf:config.allowedVideoDomains';
        $this->data['parameterArray']['fieldConf']['description'] = $lll->sL($desc) . ': ' . $config['allowedVideoDomains'];
    }
}
