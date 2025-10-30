<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\FormEngine\FieldControl;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;

final class EditHotspotControl extends AbstractNode
{
    public function render(): array
    {
        // Todo: fetch imagemap record to find out about the image. ID is in $this->data['inlineParentUid']. Or can we solve this via JS?
        $result = [
            'iconIdentifier' => 'tx_imagemap',
            'title' => "Edit Hotspot",
            'linkAttributes' => [
                'class' => 'hotspot',
                'data-tooltip' => $this->data['databaseRow']['tooltip'],
                'data-coordinates' => $this->data['databaseRow']['coordinates'],
            ],
            'javaScriptModules' => [JavaScriptModuleInstruction::create('@itzbund/gsb-core/helloworld.js')],
        ];

        return $result;
    }
}
