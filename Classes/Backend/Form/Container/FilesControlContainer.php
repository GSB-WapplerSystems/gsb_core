<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c) Luchezar Chakardzhiyan <luchesar.chakardzhiyan.ext@digitaspixelpark.com> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\Backend\Form\Container;

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

        return parent::render();
    }
}
