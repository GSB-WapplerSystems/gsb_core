<?php

/*
 * This file is part of the package itzbund/gsb-core.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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
    $GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'gsb_core/Configuration/TypoScript/';
    $GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'gsb_core/Configuration/TypoScript/ContentElement/';

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
    //$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:gsb_core/Configuration/RTE/Default.yaml';
})();
