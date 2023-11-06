# ExtendSiteConfigurationLocator

## Event

Site configuration building is extended by hooking into the `\TYPO3\CMS\Core\Configuration\Event\SiteConfigurationLoadedEvent` event.

Site configuration yaml fragments in `Configuration\SiteConfiguration\Extends\SITE\` will be merged into the site configuration `SITE`.
