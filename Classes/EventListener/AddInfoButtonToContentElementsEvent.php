<?php

namespace ITZBund\GsbCore\EventListener;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\GenericButton;
use TYPO3\CMS\Backend\Template\Components\ModifyButtonBarEvent;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class AddInfoButtonToContentElementsEvent
{
    public function __construct(
        protected readonly IconFactory $iconFactory
    ) {
    }

    public function __invoke(ModifyButtonBarEvent $event): void
    {
        $request = $this->getRequest();
        $queryParams = $request->getQueryParams();

        if (!array_key_exists('edit', $queryParams)) {
            return;
        }

        if (!array_key_exists('tt_content', $queryParams['edit'])) {
            return;
        }

        $elementUid = array_key_first($queryParams['edit']['tt_content']);

        if (!$elementUid) {
            return;
        }

        $cType = $this->getContentTypeByContentUid($elementUid);
        $contentInfo = $this->getContentInfoByType($cType);

        if (!$contentInfo) {
            return;
        }

        $buttons = $event->getButtons();

        $buttons[ButtonBar::BUTTON_POSITION_RIGHT][95][] = $this->generateInfoButton(
            $contentInfo['title'],
            $contentInfo['link']
        );

        $event->setButtons($buttons);
    }

    /**
     * @param string $title
     * @param string $link
     * @return GenericButton
     */
    private function generateInfoButton(string $title, string $link): GenericButton
    {
        return GeneralUtility::makeInstance(GenericButton::class)
            ->setTag('a')
            ->setHref($link)
            ->setAttributes(['target' => '_blank'])
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

    protected function getContentInfoByType($cType): ?array
    {
        if (!array_key_exists($cType, $GLOBALS['TCA']['tt_content']['types'])) {
            return null;
        }

        if (!array_key_exists('infoButton', $GLOBALS['TCA']['tt_content']['types'][$cType])) {
            return null;
        }

        return $GLOBALS['TCA']['tt_content']['types'][$cType]['infoButton'];
    }

    /**
     * @param int $uid
     * @return string
     */
    protected function getContentTypeByContentUid(int $uid): string
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');

        return $queryBuilder
            ->select('CType')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid))
            )
            ->executeQuery()
            ->fetchOne();
    }
}
