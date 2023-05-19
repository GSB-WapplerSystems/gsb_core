<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\EventListener;

use ITZBund\GsbCore\Configuration\PackageHelper;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\TypoScript\IncludeTree\Event\AfterTemplatesHaveBeenDeterminedEvent;

/*
 * This file is part of TYPO3 CMS-extension "gsb_core".
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * Highly inspired by the "bolt" extension by b13.
 */


final class AddTypoScriptFromSitePackageEvent
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
        if (!$site instanceof Site) {
            return;
        }

        $package = $this->packageHelper->getSitePackageFromSite($site);
        if ($package === null) {
            return;
        }

        $constants = $this->loadFileContent($package->getPackagePath() . self::TEMPLATE_CONSTANTS_FILE);
        $setup = $this->loadFileContent($package->getPackagePath() . self::TEMPLATE_SETUP_FILE);

        if ($constants === null && $setup === null) {
            return;
        }

        $siteRootPageId = $site->getRootPageId();
        $rootline = $event->getRootline();
        $sysTemplateRows = $event->getTemplateRows();
        $highestUid = $this->getHighestUid($sysTemplateRows);

        $fakeRow = $this->createFakeRow($package, $highestUid, $constants, $setup);

        if (empty($sysTemplateRows)) {
            $sysTemplateRows[] = $fakeRow;
            $event->setTemplateRows($sysTemplateRows);
            return;
        }

        $newSysTemplateRows = $this->insertFakeRow($sysTemplateRows, $rootline, $siteRootPageId, $fakeRow);
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

    private function getHighestUid(array $sysTemplateRows): int
    {
        $highestUid = 1;
        foreach ($sysTemplateRows as $sysTemplateRow) {
            if ((int)($sysTemplateRow['uid'] ?? 0) > $highestUid) {
                $highestUid = (int)$sysTemplateRow['uid'];
            }
        }
        return $highestUid;
    }

    private function createFakeRow($package, int $highestUid, ?string $constants, ?string $setup): array
    {
        $fakeRow = [
            'uid' => $highestUid + 1,
            'pid' => $siteRootPageId,
            'title' => 'Site extension include EXT:' . $package->getPackageKey() . ' by EXT:gsb_core',
            'root' => 1,
            'clear' => 3,
            'include_static_file' => null,
            'constants' => (string)$constants,
            'config' => (string)$setup,
            'isExtGsbCoreFakeRow' => true,
        ];

        // Set various "db" fields conditionally
        $this->setDbFields($fakeRow);

        return $fakeRow;
    }

    private function setDbFields(array &$fakeRow): void
    {
        $deleteField = $GLOBALS['TCA']['sys_template']['ctrl']['delete'] ?? null;
        if ($deleteField) {
            $fakeRow[$deleteField] = 0;
        }

        $disableField = $GLOBALS['TCA']['sys_template']['ctrl']['enablecolumns']['disabled'] ?? null;
        if ($disableField) {
            $fakeRow[$disableField] = 0;
        }

        // Set other fields...

        if ($GLOBALS['TCA']['sys_template']['columns']['static_file_mode'] ?? false) {
            $fakeRow['static_file_mode'] = 0;
        }
    }

    private function insertFakeRow(array $sysTemplateRows, array $rootline, int $siteRootPageId, array $fakeRow): array
    {
        $newSysTemplateRows = [];
        $pidsBeforeSite = $this->getPidsBeforeSite($rootline, $siteRootPageId);
        $fakeRowAdded = false;

        foreach ($sysTemplateRows as $sysTemplateRow) {
            if ($fakeRowAdded) {
                $newSysTemplateRows[] = $sysTemplateRow;
                continue;
            }

            if (in_array((int)($sysTemplateRow['pid'] ?? 0), $pidsBeforeSite)) {
                $newSysTemplateRows[] = $sysTemplateRow;
                $fakeRow['clear'] = 0;
            } elseif ((int)($sysTemplateRow['pid'] ?? 0) === $siteRootPageId) {
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
        return $newSysTemplateRows;
    }

    private function getPidsBeforeSite(array $rootline, int $siteRootPageId): array
    {
        $pidsBeforeSite = [0];
        $reversedRootline = array_reverse($rootline);

        foreach ($reversedRootline as $page) {
            if (($page['uid'] ?? 0) !== $siteRootPageId) {
                $pidsBeforeSite[] = (int)($page['uid'] ?? 0);
            } else {
                break;
            }
        }

        return array_unique($pidsBeforeSite);
    }
}
