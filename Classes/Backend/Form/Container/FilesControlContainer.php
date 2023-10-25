<?php

namespace ITZBund\GsbCore\Backend\Form\Container;

use TYPO3\CMS\Core\Utility\ArrayUtility;

class FilesControlContainer extends \TYPO3\CMS\Backend\Form\Container\FilesControlContainer
{
    public function render()
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

            ArrayUtility::mergeRecursiveWithOverrule(
                $this->data['processedTca']['ctrl']['container']['file']['fieldInformation'],
                $this->data['parameterArray']['fieldConf']['config']['fieldInformation']
            );
        }

        return parent::render();
    }
}
