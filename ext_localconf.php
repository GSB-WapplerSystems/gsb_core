<?php

/*
 * This file is part of the package itzbund/gsb-template.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die('Access denied.');

/***************
 * Define TypoScript as content rendering template
 */
$GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'gsb_template/Configuration/TypoScript/';
$GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'gsb_template/Configuration/TypoScript/ContentElement/';

// Make the extension configuration accessible
$extensionConfiguration = GeneralUtility::makeInstance(
    ExtensionConfiguration::class
);

/***************
 * PageTS
 */

// Add Content Elements
if (!(bool) $extensionConfiguration->get('gsb_template', 'disablePageTsContentElements')) {
    ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:gsb_template/Configuration/TsConfig/Page/ContentElement/All.tsconfig">');
}

// Add BackendLayouts for the BackendLayout DataProvider
if (!(bool) $extensionConfiguration->get('gsb_template', 'disablePageTsContentElements')) {
    ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:gsb_template/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts.tsconfig">');
}

// RTE
if (!(bool) $extensionConfiguration->get('gsb_template', 'disablePageTsRTE')) {
    ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:gsb_template/Configuration/TsConfig/Page/RTE.tsconfig">');
}

// TCADefaults
if (!(bool) $extensionConfiguration->get('gsb_template', 'disablePageTsTCADefaults')) {
    ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:gsb_template/Configuration/TsConfig/Page/TCADefaults.tsconfig">');
}

// TCEMAIN
if (!(bool) $extensionConfiguration->get('gsb_template', 'disablePageTsTCEMAIN')) {
    ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:gsb_template/Configuration/TsConfig/Page/TCEMAIN.tsconfig">');
}

// TCEFORM
if (!(bool) $extensionConfiguration->get('gsb_template', 'disablePageTsTCEFORM')) {
    ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:gsb_template/Configuration/TsConfig/Page/TCEFORM.tsconfig">');
}

// Register custom EXT:form configuration
if (ExtensionManagementUtility::isLoaded('form')) {
    ExtensionManagementUtility::addTypoScriptSetup(trim('
        module.tx_form {
            settings {
                yamlConfigurations {
                    110 = EXT:gsb_template/Resources/Extensions/form/Yaml/BaseSetup.yaml
                }
            }
        }
        plugin.tx_form {
            settings {
                yamlConfigurations {
                    110 = EXT:gsb_template/Resources/Extensions/form/Yaml/BaseSetup.yaml
                }
            }
        }
    '));
}

// Add default RTE configuration for the template package
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:gsb_template/Configuration/RTE/Default.yaml';

(function () {
    /**
     * Register icons
     */
    $iconRegistry = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Imaging\IconRegistry::class);
    // Content elements
    $icons = [
        'Audio', 'Video', 'Stage', 'Gallery', 'Grid', 'Container', 'Tabs', 'Accordion', 'Columns2', 'Columns3', 'Columns4', 'Frame', 'NoFrame'
    ];
    foreach ($icons as $icon) {
        $iconRegistry->registerIcon(
            'tx_' . strtolower($icon),
            TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:gsb_template/Resources/Public/Images/Icons/' . $icon . '.svg']
        );
    }
})();
