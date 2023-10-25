<?php

namespace ITZBund\GsbCore\Backend\Form\Container;

use TYPO3\CMS\Core\Utility\ArrayUtility;

class OuterWrapContainer extends \TYPO3\CMS\Backend\Form\Container\OuterWrapContainer
{
    public function render()
    {
        if (array_key_exists('container', $this->data['processedTca']['ctrl'])
            && array_key_exists('outerWrapContainer', $this->data['processedTca']['ctrl']['container'])
            && array_key_exists($this->data['recordTypeValue'], $this->data['processedTca']['ctrl']['container']['outerWrapContainer'])
            && array_key_exists('fieldInformation', $this->data['processedTca']['ctrl']['container']['outerWrapContainer'][$this->data['recordTypeValue']])

        ) {

            if (!array_key_exists('fieldInformation', $this->data['processedTca']['ctrl']['container']['outerWrapContainer'])) {
                $this->data['processedTca']['ctrl']['container']['outerWrapContainer']['fieldInformation'] = [];
            }

            ArrayUtility::mergeRecursiveWithOverrule(
                $this->data['processedTca']['ctrl']['container']['outerWrapContainer']['fieldInformation'],
                $this->data['processedTca']['ctrl']['container']['outerWrapContainer'][$this->data['recordTypeValue']]['fieldInformation']
            );
        }

        return parent::render();
    }
}
