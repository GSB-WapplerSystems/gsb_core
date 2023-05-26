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

        $highestUid = 1;
        foreach ($sysTemplateRows as $sysTemplateRow) {
            if ((int)($sysTemplateRow['uid'] ?? 0) > $highestUid) {
                $highestUid = (int)$sysTemplateRow['uid'];
            }
        }


        $fakeRow = $this->getFakeRow($highestUid, $site, $package, $constants, $setup);

        if (empty($sysTemplateRows)) {
            $event->setTemplateRows([$fakeRow]);
            return;
        }

        $newSysTemplateRows = $this->getSysTemplateRows($event, $sysTemplateRows, $fakeRow, $site);

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
        // Set various "db" fields conditionally to be as robust as possible in case
        // core or some other loaded extension fiddles with them.
        $deleteField = $GLOBALS['TCA']['sys_template']['ctrl']['delete'] ?? null;
        if ($deleteField) {
            $fakeRow[$deleteField] = 0;
        }
        $disableField = $GLOBALS['TCA']['sys_template']['ctrl']['enablecolumns']['disabled'] ?? null;
        if ($disableField) {
            $fakeRow[$disableField] = 0;
        }
        $endtimeField = $GLOBALS['TCA']['sys_template']['ctrl']['enablecolumns']['endtime'] ?? null;
        if ($endtimeField) {
            $fakeRow[$endtimeField] = 0;
        }
        $starttimeField = $GLOBALS['TCA']['sys_template']['ctrl']['enablecolumns']['starttime'] ?? null;
        if ($starttimeField) {
            $fakeRow[$starttimeField] = 0;
        }
        $sortbyField = $GLOBALS['TCA']['sys_template']['ctrl']['sortby'] ?? null;
        if ($sortbyField) {
            $fakeRow[$sortbyField] = 0;
        }
        $tstampField = $GLOBALS['TCA']['sys_template']['ctrl']['tstamp'] ?? null;
        /*if ($tstampField) {
            $fakeRow[$tstampField] = ($setup ? filemtime($setupFile) : null) ?? ($constants ? filemtime($constantsFile) : null) ?? time();
        }*/
        if ($GLOBALS['TCA']['sys_template']['columns']['basedOn'] ?? false) {
            $fakeRow['basedOn'] = null;
        }
        if ($GLOBALS['TCA']['sys_template']['columns']['includeStaticAfterBasedOn'] ?? false) {
            $fakeRow['includeStaticAfterBasedOn'] = 0;
        }
        if ($GLOBALS['TCA']['sys_template']['columns']['static_file_mode'] ?? false) {
            $fakeRow['static_file_mode'] = 0;
        }
    }

    /**
     * @param mixed $highestUid
     * @param Site $site
     * @param \TYPO3\CMS\Core\Package\PackageInterface $package
     * @param string|null $constants
     * @param string|null $setup
     * @return array
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
     * @param array $sysTemplateRows
     * @param array $fakeRow
     * @param Site $site
     * @return array
     */
    public function getSysTemplateRows(AfterTemplatesHaveBeenDeterminedEvent|MockAfterTemplatesHaveBeenDeterminedEvent $event, array $sysTemplateRows, array $fakeRow, Site $site): array
    {
        $newSysTemplateRows = [];
        $pidsBeforeSite = array_unique(array_merge([0], array_column(array_reverse($event->getRootline()), 'uid')));
        $fakeRowAdded = false;

        foreach ($sysTemplateRows as $sysTemplateRow) {
            if ($fakeRowAdded) {
                // We added the fake row already. Just add all other templates below this.
                $newSysTemplateRows[] = $sysTemplateRow;
                continue;
            }

            $sysTemplateRowPid = (int)($sysTemplateRow['pid'] ?? 0);
            if (in_array($sysTemplateRowPid, $pidsBeforeSite)) {
                $newSysTemplateRows[] = $sysTemplateRow;
                // If there is a sys_template row *before* our site, we assume settings from above
                // templates should "fall through", so we unset the clear flags. If this is not
                // wanted, an instance may need to register another event listener after this one
                // to set the clear flag again.
                $fakeRow['clear'] = 0;
            } elseif ($sysTemplateRowPid === $site->getRootPageId()) {
                // There is a sys_template on the site root page already. We add our fake row before
                // this one, then force the root and the clear flag of the sys_template row to 0.
                $newSysTemplateRows[] = $fakeRow;
                $fakeRowAdded = true;
                $sysTemplateRow['root'] = 0;
                $sysTemplateRow['clear'] = 0;
                $newSysTemplateRows[] = $sysTemplateRow;
            } else {
                // Not a sys_template row before, not an sys_template record on same page. Add our
                // fake row and mark we added it.
                $newSysTemplateRows[] = $fakeRow;
                $fakeRowAdded = true;
            }
        }
        return $newSysTemplateRows;
    }
}
