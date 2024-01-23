<?php

/*
* This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund
*
* (c) Christian Rath-Ulrich <christian.rath-ulrich@digitaspixelpark.com> 2023
* (c) Kai Ole Hartwig <o.hartwig@moselwal.de> 2023
* (c) Matthias Peltzer <matthias.peltzer@digitaspixelpark.com> 2023
* (c) Luchezar Chakardzhiyan <luchesar.chakardzhiyan.ext@digitaspixelpark.com> 2023
*
* It is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License, either version 2
* of the License, or any later version.
*
* For the full copyright and license information, please read the
* LICENSE file that was distributed with this source code.
*/

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die('Access denied.');

(function () {
    // @todo Check after implementation of  Feature https://forge.typo3.org/issues/100056
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['cspForBitvTestTools'] ??= true;
    // Future Security Headers
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['crossOriginEmbedderPolicy'] ??= false;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['crossOriginOpenerPolicy'] ??= false;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['crossOriginResourcePolicy'] ??= false;

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

    // override youtube helper to support offline mode
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\YouTubeHelper::class] = [
        'className' => \ITZBund\GsbCore\Resources\OnlineMedia\Helpers\OverrideYouTubeHelper::class,
    ];

    // override vimeo helper to support offline mode
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\VimeoHelper::class] = [
        'className' => \ITZBund\GsbCore\Resources\OnlineMedia\Helpers\OverrideVimeoHelper::class,
    ];

    // xclass core update service to respect offline mode
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Install\Service\CoreUpdateService::class] = [

        'className' => \ITZBund\GsbCore\Xclass\CoreUpdateService::class,
    ];

    // xclass Core Version Service to respect offline mode
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Install\Service\CoreVersionService::class] = [
        'className' => \ITZBund\GsbCore\Xclass\CoreVersionService::class,
    ];

    $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
    // Only include user.tsconfig if TYPO3 version is below 13 so that it is not imported twice.
    if ($versionInformation->getMajorVersion() < 13) {
        ExtensionManagementUtility::addUserTSConfig(
            '@import "EXT:gsb_core/Configuration/user.tsconfig"'
        );
    }
})();
