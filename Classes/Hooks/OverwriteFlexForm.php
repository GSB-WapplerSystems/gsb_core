<?php

declare(strict_types=1);

namespace ITZBund\GsbTemplate\Hooks;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class OverwriteFlexForm
 */
class OverwriteFlexForm
{
    public function overwrite()
    {
        ExtensionManagementUtility::addPiFlexFormValue(
            'solr_pi_results',
            'FILE:EXT:gsb_template/Configuration/Extensions/solr/FlexForms/Results.xml'
        );
    }
}
