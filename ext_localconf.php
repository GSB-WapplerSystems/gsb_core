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

use ITZBund\GsbCore\Resource\OnlineMedia\Helpers\GenericExternalAudioHelper;
use ITZBund\GsbCore\Resource\OnlineMedia\Helpers\GenericExternalVideoHelper;
use ITZBund\GsbCore\Resource\Rendering\GenericExternalAudioRenderer;
use ITZBund\GsbCore\Resource\Rendering\GenericExternalVideoRenderer;
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die('Access denied.');

(function () {
    // @todo Check after implementation of  Feature https://forge.typo3.org/issues/100056
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['GSB11_OPTION_618_BITV_TEST_TOOLS'] ??= false;
    // Future Security Headers
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['crossOriginEmbedderPolicy'] ??= false;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['crossOriginOpenerPolicy'] ??= false;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['crossOriginResourcePolicy'] ??= false;

    // Branded backend login screen
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['GSB11_OPTION_1972_GSB11_BACKEND_BRANDING'] ??= false;
    if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('GSB11_OPTION_1972_GSB11_BACKEND_BRANDING')) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['backendFavicon'] = 'EXT:gsb_core/Resources/Public/Images/logo.png';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['backendLogo'] = 'EXT:gsb_core/Resources/Public/Images/logo.png';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage'] = 'EXT:gsb_core/Resources/Public/Images/bg.jpg';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginFootnote'] = '© GSB - ITZBund';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginHighlightColor'] = '#004b76';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginLogo'] = 'EXT:gsb_core/Resources/Public/Images/logo.png';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginLogoAlt'] = 'GSB - ITZBund';
    }

    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths']['100'] = 'EXT:gsb_core/Resources/Private/Layouts/Email/';

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

    ExtensionManagementUtility::addTypoScriptSetup(trim('
        plugin.tx_form {
            settings {
                yamlConfigurations {
                    122 = EXT:form_mailtext/Configuration/Form/MailtextFormSetup.yaml
                    123 = EXT:gsb_core/Resources/Extensions/form/Yaml/ExtendedMailtextFormSetup.yaml
                }
            }
        }
        module.tx_form {
            settings {
                yamlConfigurations {
                    122 = EXT:form_mailtext/Configuration/Form/MailtextFormSetup.yaml
                    123 = EXT:gsb_core/Resources/Extensions/form/Yaml/ExtendedMailtextFormSetup.yaml
                }
            }
        }
    '));

    /***************
     * Define TypoScript as content rendering template
     */
    // $GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'gsb_core/Configuration/TypoScript/';

    $extVideoFileExtension = 'externalvideo';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers'][$extVideoFileExtension] = GenericExternalVideoHelper::class;

    /** @var RendererRegistry $rendererRegistry */
    $rendererRegistry = GeneralUtility::makeInstance(RendererRegistry::class);
    $rendererRegistry->registerRendererClass(GenericExternalVideoRenderer::class);

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType'][$extVideoFileExtension] = 'video/generic';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',' . $extVideoFileExtension;

    /** @var IconRegistry $iconRegistry */
    $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerFileExtension($extVideoFileExtension, 'mimetypes-media-video');

    $extAudioFileExtension = 'externalaudio';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers'][$extAudioFileExtension] = GenericExternalAudioHelper::class;

    /** @var RendererRegistry $rendererRegistry */
    $rendererRegistry = GeneralUtility::makeInstance(RendererRegistry::class);
    $rendererRegistry->registerRendererClass(GenericExternalAudioRenderer::class);

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType'][$extAudioFileExtension] = 'audio/generic';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',' . $extAudioFileExtension;

    /** @var IconRegistry $iconRegistry */
    $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerFileExtension($extAudioFileExtension, 'mimetypes-media-audio');

    // Add default RTE configuration for the template package
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:gsb_core/Configuration/RTE/Default.yaml';

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
            \ITZBund\GsbCore\Resource\OnlineMedia\Helpers\OverrideYouTubeHelper::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['vimeo'] =
            \ITZBund\GsbCore\Resource\OnlineMedia\Helpers\OverrideVimeoHelper::class;
    }

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['FrontendEditing']['DataProcessing']['custom_category_processor'] = \ITZBund\GsbCore\DataProcessing\CustomPageCategoryProcessor::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['f'][] = 'ITZBund\\GsbCore\\Fluid\\ViewHelpers';

    // Configure caching framework
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['gsb_core_menu'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'options' => ['defaultLifetime' => 2592000], // 30 days
        'groups' => ['pages'],
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:backend/Resources/Private/Language/locallang.xlf'][] = 'EXT:gsb_core/Resources/Private/Backend/LanguageOverrides/de.locallang.xlf';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\ITZBund\GsbCore\Evaluation\HttpsUrlEvaluation::class] = '';
})();
