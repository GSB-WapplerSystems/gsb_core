<?php

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
                $text = '<a href="' . $textConfiguration['link'] . '" target="_blank">' . $text . '</a>';
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
