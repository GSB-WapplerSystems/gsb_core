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

namespace ITZBund\GsbCore\Backend;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Localization\LanguageService;

class ElementInformationText extends AbstractNode
{
    /**
     * @return array<mixed>
     */
    public function render(): array
    {
        $languageService = $this->getLanguageService();

        $texts = [];
        foreach ((array)($this->data['renderData']['fieldInformationOptions']['texts'] ?? []) as $textConfiguration) {
            $text = htmlspecialchars($languageService->sL($textConfiguration['text']));

            if (array_key_exists('italic', $textConfiguration) && $textConfiguration['italic']) {
                $text = '<em>' . $text . '</em>';
            }

            if (array_key_exists('bold', $textConfiguration) && $textConfiguration['bold']) {
                $text = '<strong>' . $text . '</strong>';
            }

            if (array_key_exists('link', $textConfiguration)) {
                $linkTarget = array_key_exists('linkTarget', $textConfiguration) ? $textConfiguration['linkTarget'] : '_blank';
                $text = '<a href="' . $textConfiguration['link'] . '" target="' . $linkTarget . '">' . $text . '</a>';
            }

            $texts[] = $text;
        }

        return [
            'html' => '<div class="form-control-wrap">'
                . implode(' ', $texts)
                . '</div>',
        ];
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
