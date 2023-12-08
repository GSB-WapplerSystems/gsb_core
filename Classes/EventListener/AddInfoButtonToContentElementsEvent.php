<?php

/*
 * This file is part of the package itzbund/gsb-core of the GSB 11 Project by ITZBund.
 *
 * (c)  Luchezar Chakardzhiyan <luchesar.chakardzhiyan.ext@digitaspixelpark.com> 2023
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace ITZBund\GsbCore\EventListener;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\GenericButton;
use TYPO3\CMS\Backend\Template\Components\ModifyButtonBarEvent;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class AddInfoButtonToContentElementsEvent
{
    public function __construct(
        protected readonly IconFactory $iconFactory,
        protected readonly ConnectionPool $connectionPool
    ) {}

    public function __invoke(ModifyButtonBarEvent $event): void
    {
        $request = $this->getRequest();
        $queryParams = $request->getQueryParams();

        if (!array_key_exists('edit', $queryParams)) {
            return;
        }

        if (!is_array($queryParams['edit']) || count($queryParams['edit']) == 0) {
            return;
        }

        $table = array_key_first($queryParams['edit']);
        $elementUid = array_key_first($queryParams['edit'][$table]) ?? false;

        if (!$elementUid || !is_numeric($elementUid)) {
            return;
        }

        $type = $this->getTypeByTableAndUid($table, (int)$elementUid);

        if ($type == null) {
            return;
        }

        $contentInfo = $GLOBALS['TCA'][$table]['types'][$type]['infoButton'] ?? false;

        if (!$contentInfo) {
            return;
        }

        $buttons = $event->getButtons();

        $buttons[ButtonBar::BUTTON_POSITION_RIGHT][95][] = $this->generateInfoButton(
            $contentInfo['title'],
            $contentInfo['link'],
            array_key_exists('linkTarget', $contentInfo) ? $contentInfo['linkTarget'] : '_blank'
        );

        $event->setButtons($buttons);
    }

    /**
     * @param string $title
     * @param string $link
     * @param string $linkTarget
     * @return GenericButton
     */
    private function generateInfoButton(string $title, string $link, string $linkTarget): GenericButton
    {
        return GeneralUtility::makeInstance(GenericButton::class)
            ->setTag('a')
            ->setHref($link)
            ->setAttributes(['target' => $linkTarget])
            ->setTitle($title)
            ->setIcon($this->iconFactory->getIcon('module-help'));
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }

    /**
     * Get the record type for this record
     */
    protected function getTypeByTableAndUid(string $table, int $uid): ?string
    {
        $typeField = $GLOBALS['TCA'][$table]['ctrl']['type'] ?? false;
        if ($typeField) {
            $record = BackendUtility::getRecord($table, $uid);
            if (isset($record[$typeField])) {
                return (string)$record[$typeField];
            }
        }

        return null;
    }
}
