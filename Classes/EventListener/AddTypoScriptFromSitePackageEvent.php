<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\EventListener;

use ITZBund\GsbCore\Configuration\PackageHelper;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\TypoScript\IncludeTree\Event\AfterTemplatesHaveBeenDeterminedEvent;

class AddTypoScriptFromSitePackageEvent
{
    protected PackageHelper $packageHelper;
    private const TEMPLATE_CONSTANTS_FILE = 'Configuration/TypoScript/constants.typoscript';
    private const TEMPLATE_SETUP_FILE = 'Configuration/TypoScript/setup.typoscript';

    public function __construct(PackageHelper $packageHelper)
    {
        $this->packageHelper = $packageHelper;
    }

    public function __invoke(AfterTemplatesHaveBeenDeterminedEvent $event): void
    {
        $site = $event->getSite();
        if (!($site instanceof Site) || !($package = $this->packageHelper->getSitePackageFromSite($site))) {
            return;
        }

        $constants = $this->loadFileContent($package->getPackagePath() . self::TEMPLATE_CONSTANTS_FILE);
        $setup = $this->loadFileContent($package->getPackagePath() . self::TEMPLATE_SETUP_FILE);
        if ($constants === null && $setup === null) {
            return;
        }

        $sysTemplateRows = $event->getTemplateRows();
        $highestUid = max(array_column($sysTemplateRows, 'uid') ?: [0]);

        $fakeRow = [
            'uid' => $highestUid + 1,
            'pid' => $site->getRootPageId(),
            'title' => 'Site extension include EXT:' . $package->getPackageKey() . ' by EXT:gsb_core',
            'root' => 1,
            'clear' => 3,
            'include_static_file' => null,
            'constants' => (string)$constants,
            'config' => (string)$setup,
            'isExtGsbCoreFakeRow' => true,
        ];

        $this->setDbFields($fakeRow);

        if (empty($sysTemplateRows)) {
            $event->setTemplateRows([$fakeRow]);
            return;
        }

        $newSysTemplateRows = [];
        $pidsBeforeSite = array_merge([0], array_column(array_reverse($event->getRootline()), 'uid'));
        $fakeRowAdded = false;

        foreach ($sysTemplateRows as $sysTemplateRow) {
            if ($fakeRowAdded) {
                $newSysTemplateRows[] = $sysTemplateRow;
                continue;
            }

            $sysTemplateRowPid = (int)($sysTemplateRow['pid'] ?? 0);
            if (in_array($sysTemplateRowPid, $pidsBeforeSite)) {
                $newSysTemplateRows[] = $sysTemplateRow;
                $fakeRow['clear'] = 0;
            } elseif ($sysTemplateRowPid === $site->getRootPageId()) {
                $newSysTemplateRows[] = $fakeRow;
                $fakeRowAdded = true;
                $sysTemplateRow['root'] = 0;
                $sysTemplateRow['clear'] = 0;
                $newSysTemplateRows[] = $sysTemplateRow;
            } else {
                $newSysTemplateRows[] = $fakeRow;
                $fakeRowAdded = true;
            }
        }

        $event->setTemplateRows($newSysTemplateRows);
    }

    private function loadFileContent(string $file): ?string
    {
        if (file_exists($file) && is_readable($file)) {
            $fileContent = file_get_contents($file);
            if ($fileContent !== false) {
                return $fileContent;
            }
        }
        return null;
    }

    private function setDbFields(array &$fakeRow): void
    {
        $GLOBALS['TCA']['sys_template']['ctrl']['delete'] && $fakeRow[$GLOBALS['TCA']['sys_template']['ctrl']['delete']] = 0;
        $GLOBALS['TCA']['sys_template']['ctrl']['enablecolumns']['disabled'] && $fakeRow[$GLOBALS['TCA']['sys_template']['ctrl']['enablecolumns']['disabled']] = 0;
        $GLOBALS['TCA']['sys_template']['columns']['static_file_mode'] && $fakeRow['static_file_mode'] = 0;
    }
}
