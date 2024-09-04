<?php

declare(strict_types=1);

namespace ITZBund\GsbCore\Utility;

use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class ExtendSiteUtility
{
    public static function extendSiteWithLocalizationOverload(Site $site, SiteLanguage $language): Site
    {
        $localizedConfig = static::overloadWithLocalizedConfig($site->getConfiguration(), $language->getLanguageId());

        return new Site($site->getIdentifier(), $site->getRootPageId(), $localizedConfig, $site->getSettings());
    }

    /**
     * @param array<string,mixed> $config
     * @return array<string,mixed>
     */
    public static function overloadWithLocalizedConfig(array $config, int $languageId): array
    {
        $languages = $config['languages'] ?? [];

        $languageConfig = array_filter($languages, function (array $language) use ($languageId) {
            return (int)$language['languageId'] === $languageId;
        });

        if (count($languageConfig) !== 1) {
            return $config;
        }

        $languageConfig = reset($languageConfig);

        foreach ($languageConfig as $key => $value) {
            if (!isset($config[$key])) {
                continue;
            }
            $config[$key] = $value;
        }

        return $config;
    }

    /**
     * @param array<string,mixed> $config
     * @return array<string,mixed>
     */
    public static function copyToggleFieldsToLanguageConfigs(array $config): array
    {
        $toggleFields = self::getLocalizationToggleFields($config);

        foreach ($config['languages'] as &$languageConfig) {
            foreach ($toggleFields as $toggleFieldKey) {
                $languageConfig[$toggleFieldKey] = $config[$toggleFieldKey];
            }
        }

        return $config;
    }

    /**
     * @param array<string,mixed> $config
     * @return string[]
     */
    public static function getLocalizationToggleFields(array $config): array
    {
        return array_values(array_filter(
            array_keys($config),
            function (string $key) {
                return str_contains($key, 'toggle');
            }
        ));
    }
}
