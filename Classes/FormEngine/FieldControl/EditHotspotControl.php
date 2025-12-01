<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\FormEngine\FieldControl;

use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class EditHotspotControl extends AbstractNode
{
    /**
     * @return mixed[]
     */
    public function render(): array
    {
        $result = [];
        $image = $this->getImage();
        if ($image && isset($image['identifier'])) {
            $result = [
                'iconIdentifier' => 'tx_imagemap_hotspot_rounded',
                'title' => 'Edit Hotspot',
                'linkAttributes' => [
                    'class' => 'hotspot',
                    'data-tooltip' => $this->data['databaseRow']['tooltip'],
                    'data-coordinates' => $this->data['databaseRow']['coordinates'],
                    'data-image' => $image['identifier'],
                ],
                'stylesheetFiles' => ['EXT:gsb_core/Resources/Public/Build/StyleSheets/interactiveImageBackend.css'],
                'javaScriptModules' => [JavaScriptModuleInstruction::create('@itzbund/gsb_core/interactiveImage/backend.js')],
            ];
        }
        return $result;
    }

    /**
     * @return mixed[]
     */
    private function getImage(): ?array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_reference');
        $sysFileReference = $queryBuilder
            ->select('*')
            ->from('sys_file_reference')
            ->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($this->data['inlineParentUid'], ParameterType::INTEGER)),
                    $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter('tt_content', ParameterType::STRING))
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($sysFileReference) {
            return BackendUtility::getRecord('sys_file', $sysFileReference['uid_local']);
        }
        return null;
    }
}
