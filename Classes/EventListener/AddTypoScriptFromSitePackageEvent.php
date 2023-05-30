<?php

declare(strict_types=1);

/*
 * This file is part of TYPO3 CMS-extension "gsb_core".
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * Highly inspired by the "bolt" extension by b13.
 */

namespace ITZBund\GsbCore\EventListener;

use ITZBund\GsbCore\Configuration\PackageHelper;
use ITZBund\GsbCore\Tests\Unit\EventListener\MockAfterTemplatesHaveBeenDeterminedEvent;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\TypoScript\IncludeTree\Event\AfterTemplatesHaveBeenDeterminedEvent;

/**
 * Event listener in FE and BE (ext:tstemplate) to add a typoscript template "fake" sys_template
 * row that auto-adds TypoScript when the site has a 'sitePackage' set and has a
 * "Configuration/TypoScript/constants.typoscript" or "Configuration/TypoScript/setup.typoscript" file.
 *
 * @internal
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

        $sysTemplateRows = $event->getTemplateRows();
        $highestUid = $this->getHighestUid($sysTemplateRows);
        $fakeRow = $this->getFakeRow($highestUid, $site, $package, $constants, $setup);

        if (empty($sysTemplateRows)) {
            $event->setTemplateRows([$fakeRow]);
            return;
        }

        $newSysTemplateRows = $this->getSysTemplateRows($event, $sysTemplateRows, $fakeRow, $site);

        $event->setTemplateRows($newSysTemplateRows);
    }

    /**
     * @param array<array<string>> $sysTemplateRows
     */
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

    /**
     * @param array<string> $fakeRow
     */
    private function setDbFields(array &$fakeRow): void
    {
        $fields = [
            ['sys_template', 'ctrl', 'delete'],
            ['sys_template', 'ctrl', 'enablecolumns', 'disabled'],
            ['sys_template', 'ctrl', 'enablecolumns', 'endtime'],
            ['sys_template', 'ctrl', 'enablecolumns', 'starttime'],
            ['sys_template', 'ctrl', 'sortby'],
        ];

        foreach ($fields as $fieldPath) {
            $field = $GLOBALS['TCA'];
            foreach ($fieldPath as $key) {
                $field = $field[$key] ?? null;
                if ($field === null) {
                    break;
                }
            }
            if ($field !== null) {
                $fakeRow[$field] = 0;
            }
        }

        $columnFields = ['basedOn', 'includeStaticAfterBasedOn', 'static_file_mode'];
        foreach ($columnFields as $columnField) {
            if ($GLOBALS['TCA']['sys_template']['columns'][$columnField] ?? false) {
                $fakeRow[$columnField] = $columnField === 'basedOn' ? null : 0;
            }
        }
        /*$tstampField = $GLOBALS['TCA']['sys_template']['ctrl']['tstamp'] ?? null;
        if ($tstampField) {
            $fakeRow[$tstampField] = ($setup ? filemtime($setupFile) : null) ?? ($constants ? filemtime($constantsFile) : null) ?? time();
        }*/
    }

    /**
     * @param int $highestUid
     * @param Site $site
     * @param \TYPO3\CMS\Core\Package\PackageInterface $package
     * @param string|null $constants
     * @param string|null $setup
     * @return array<string,int>
     */
    public function getFakeRow(int $highestUid, Site $site, \TYPO3\CMS\Core\Package\PackageInterface $package, ?string $constants, ?string $setup): array
    {
        $fakeRow = [
            'uid' => $highestUid + 1,
            'pid' => $site->getRootPageId(),
            'title' => 'Site extension include EXT:' . $package->getPackageKey() . ' by EXT:gsb_core',
            'root' => 1,
            'clear' => 3,
            'include_static_file' => null,
            'constants' => (string)$constants,
            'config' => (string)$setup,
            // Add a flag. This might be useful for other event listeners that may want to manipulate again.
            'isExtGsbCoreFakeRow' => true,
        ];

        $this->setDbFields($fakeRow);
        return $fakeRow;
    }

    /**
     * @param AfterTemplatesHaveBeenDeterminedEvent|MockAfterTemplatesHaveBeenDeterminedEvent $event
     * @param array<string> $sysTemplateRows
     * @param array<string, int> $fakeRow
     * @param Site $site
     * @return array<int<0,max>,mixed>
     */
    public function getSysTemplateRows(AfterTemplatesHaveBeenDeterminedEvent|MockAfterTemplatesHaveBeenDeterminedEvent $event, array $sysTemplateRows, array $fakeRow, Site $site): array
    {
        $newSysTemplateRows = [];
        $pidsBeforeSite = array_unique(array_merge([0], array_column(array_reverse($event->getRootline()), 'uid')));
        $fakeRowAdded = false;

        foreach ($sysTemplateRows as $sysTemplateRow) {
            list($newSysTemplateRows, $fakeRow, $fakeRowAdded) = $this->handleSysTemplateRow($sysTemplateRow, $fakeRow, $newSysTemplateRows, $pidsBeforeSite, $site, $fakeRowAdded);
        }
        return $newSysTemplateRows;
    }

    /**
     * @param mixed $sysTemplateRow
     * @param array<string, int> $fakeRow
     * @param array<string> $newSysTemplateRows
     * @param array<int> $pidsBeforeSite
     * @param Site $site
     * @param bool $fakeRowAdded
     * @return array<int<0,max>,mixed>
     */
    private function handleSysTemplateRow(mixed $sysTemplateRow, array $fakeRow, array $newSysTemplateRows, array $pidsBeforeSite, Site $site, bool $fakeRowAdded): array
    {
        if ($fakeRowAdded) {
            $newSysTemplateRows[] = $sysTemplateRow;
            return [$newSysTemplateRows, $fakeRow, $fakeRowAdded];
        }

        $sysTemplateRowPid = (int)($sysTemplateRow['pid'] ?? 0);

        if (in_array($sysTemplateRowPid, $pidsBeforeSite)) {
            $newSysTemplateRows[] = $sysTemplateRow;
            $fakeRow['clear'] = 0;
            return [$newSysTemplateRows, $fakeRow, $fakeRowAdded];
        }

        if ($sysTemplateRowPid === $site->getRootPageId()) {
            $newSysTemplateRows[] = $fakeRow;
            $sysTemplateRow['root'] = 0;
            $sysTemplateRow['clear'] = 0;
            $newSysTemplateRows[] = $sysTemplateRow;
            return [$newSysTemplateRows, $fakeRow, true];
        }

        $newSysTemplateRows[] = $fakeRow;
        return [$newSysTemplateRows, $fakeRow, true];
    }
}
