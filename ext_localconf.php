<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Luchezar Chakardzhiyan, Ole Hartwig, Matthias Peltzer, Christian Rath-Ulrich, Patrick Schriner
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die('Access denied.');

(function () {
    // @todo Check after implementation of  Feature https://forge.typo3.org/issues/100056
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['cspForBitvTestTools'] ??= false;
    // Future Security Headers
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['crossOriginEmbedderPolicy'] ??= false;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['crossOriginOpenerPolicy'] ??= false;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['crossOriginResourcePolicy'] ??= false;

    // Branded backend login screen
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['brandingBackendLogin'] ??= false;
    if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('brandingBackendLogin')) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['backendFavicon'] = 'EXT:gsb_core/Resources/Public/Favicons/favicon-32x32.png';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['backendLogo'] = 'EXT:gsb_core/Resources/Public/Favicons/favicon.ico';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage'] = 'EXT:gsb_core/Resources/Public/Images/bg.jpg';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginFootnote'] = '© GSB - ITZBund';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginHighlightColor'] = '#004b76';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginLogo'] = 'EXT:gsb_core/Resources/Public/Favicons/android-chrome-192x192.png';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginLogoAlt'] = 'GSB - ITZBund';
    }

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['ITZBUNDPHP-1615'] ??= false;
    if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-1615')) {
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths']['20'] = 'EXT:gsb_core/Resources/Private/Layouts/Email/';
    }

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['ITZBUNDPHP-3327'] ??= false;

    /***************
     * Define TypoScript as content rendering template
     */
    // $GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'gsb_core/Configuration/TypoScript/';

    // Register custom EXT:form configuration
    if (ExtensionManagementUtility::isLoaded('form')) {
        ExtensionManagementUtility::addTypoScriptSetup(trim('
        module.tx_form {
            settings {
                yamlConfigurations {
                    110 = EXT:gsb_core/Resources/Extensions/form/Yaml/BaseSetup.yaml
                }
            }
        }
        plugin.tx_form {
            settings {
                yamlConfigurations {
                    110 = EXT:gsb_core/Resources/Extensions/form/Yaml/BaseSetup.yaml
                }
            }
        }
    '));
    }

    // Add default RTE configuration for the template package
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:gsb_core/Configuration/RTE/Default.yaml';
    // $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['gsb_frontend'] = 'EXT:gsb_frontend/Resources/Public/StyleSheets/ckeditor.css';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1696931020] = [
        'nodeName' => 'elementInformationText',
        'priority' => 70,
        'class' => \ITZBund\GsbCore\Backend\ElementInformationText::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\Form\Container\FilesControlContainer::class] = [
        'className' => \ITZBund\GsbCore\Backend\Form\Container\FilesControlContainer::class,
    ];

    if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['offlineMode'] ?? false) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['youtube'] =
            \ITZBund\GsbCore\Resources\OnlineMedia\Helpers\OverrideYouTubeHelper::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['vimeo'] =
            \ITZBund\GsbCore\Resources\OnlineMedia\Helpers\OverrideVimeoHelper::class;
    }

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['FrontendEditing']['DataProcessing']['custom_category_processor'] = \ITZBund\GsbCore\DataProcessing\CustomPageCategoryProcessor::class;

    $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
    // Only include user.tsconfig if TYPO3 version is below 13 so that it is not imported twice.
    if ($versionInformation->getMajorVersion() < 13) {
        ExtensionManagementUtility::addUserTSConfig(
            '@import "EXT:gsb_core/Configuration/user.tsconfig"'
        );
    }
})();
