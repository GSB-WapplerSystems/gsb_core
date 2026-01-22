<?php

declare(strict_types=1);

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * Copyright (C) 2023 - 2024 Bundesrepublik Deutschland, vertreten durch das
 * BMI/ITZBund. Author: Willi Wehmeier
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\UserFunc;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class LinkIconAriaLabelModifier
{
    /**
     * @param string $content
     * @return string
     */
    public function addAriaLabelToLinks(string $content): string
    {
        $trimmedContent = trim($content);
        if ($trimmedContent === '') {
            return $content;
        }

        $dom = $this->transformStringToDomDocument($trimmedContent);

        /** @var \DOMNodeList<\DOMElement> $links */
        $links = $dom->getElementsByTagName('a');

        $this->addAriaAttributeToExternalLinks($links);

        $result = $dom->saveHTML();

        return trim(is_string($result) ? $result : '');
    }

    /**
     * @param string $content
     * @return \DOMDocument
     */
    public function transformStringToDomDocument(string $content): \DOMDocument
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $encodedContent = mb_convert_encoding($content, 'ISO-8859-1', 'UTF-8');

        libxml_use_internal_errors(true);

        $dom->loadHTML(
            $encodedContent,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();

        return $dom;
    }

    /**
     * @param \DOMNodeList<\DOMElement> $links
     */
    public function addAriaAttributeToExternalLinks(\DOMNodeList $links): void
    {
        /** @var \DOMElement $link */
        foreach ($links as $link) {
            $this->processLink($link);
        }
    }

    /**
     * @param \DOMElement $link
     */
    private function processLink(\DOMElement $link): void
    {
        $linkType = $this->determineLinkType($link);
        $href = $link->getAttribute('href');
        $isRootRelative = str_starts_with($href, '/');

        if ($isRootRelative && $linkType !== 4) {
            return;
        }

        if ($linkType <= 0) {
            $this->addAriaAttributeToSpanIcons($link, 'aria-hidden', 'true');
            return;
        }

        if (!$link->hasAttribute('aria-label')) {
            $this->addAriaLabelToLink($link, $linkType);
        }
    }

    /**
     * @param \DOMElement $link
     * @return int
     */
    private function determineLinkType(\DOMElement $link): int
    {
        $classNames = $link->getAttribute('class');

        return match ($classNames) {
            'external-link' => 1,
            'phone' => 2,
            'email' => 3,
            'internal-link' => 4,
            default => 0,
        };
    }

    /**
     * @param \DOMElement $link
     * @param int $linkType
     */
    private function addAriaLabelToLink(\DOMElement $link, int $linkType): void
    {
        $ariaLabel = LocalizationUtility::translate(
            'linkTypeAriaLabel.' . $linkType,
            'gsb_core'
        );

        if ($link->firstChild === null || $ariaLabel === null) {
            return;
        }

        $this->addAriaAttributeToSpanIcons($link, 'aria-label', $ariaLabel);
    }

    /**
     * addAriaAttributeToSpanIcons
     * @param \DOMElement $link
     * @param string $attributeName
     * @param string $attributeValue
     */
    public function addAriaAttributeToSpanIcons(\DOMElement &$link, string $attributeName, string $attributeValue): void
    {
        $firstChild = $link->firstChild;
        if ($firstChild instanceof \DOMElement && $firstChild->getAttribute('class') === 'icon') {
            $firstChild->setAttribute($attributeName, $attributeValue);
        }
    }
}
