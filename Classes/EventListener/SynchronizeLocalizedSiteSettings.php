<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\EventListener;

use ITZBund\GsbCore\Utility\ExtendSiteUtility;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationBeforeWriteEvent;
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class SynchronizeLocalizedSiteSettings
{
    public function __invoke(SiteConfigurationBeforeWriteEvent $event): void
    {
        if (! GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-3288')) {
            return;
        }

        $settings = $event->getConfiguration();
        $updatedSettings = ExtendSiteUtility::copyToggleFieldsToLanguageConfigs($settings);
        $event->setConfiguration($updatedSettings);
    }
}
