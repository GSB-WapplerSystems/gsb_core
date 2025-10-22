<!--
SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

SPDX-License-Identifier: GPL-3.0-or-later
-->

<!-- PROJECT SHIELDS -->

# GSB&nbsp;11 Extension gsb_core

[![TYPO3 13](https://img.shields.io/badge/TYPO3-13-orange.svg)](https://get.typo3.org/version/13)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-%23777BB4.svg?logo=php&logoColor=white)](https://www.php.net/releases/8.3/en.php)

## About

The extension gsb_core is the mainstay of the several GSB&nbsp;11 extensions. The extension configures TYPO3 and extends it with selected extensions to provide better structured and appealing content while optimizing accessibility.

[Learn more about the GSB&nbsp;11][gsb11-readme-url].

## Installation

The best way to install this extension is to start with the [GSB Sitepackage Kickstarter][kickstarter-url] extension.

## Quick installation without GSB Sitepackage Kickstarter

In a composer-based TYPO3 installation you can install the extension EXT:gsb_core via composer:

```sh
  composer config -g gitlab-domains gitlab.opencode.de && \
  composer config -g repositories.gsb-core vcs https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/gsb_core.git
```

```sh
  composer require itzbund/gsb-core
```

In TYPO3 installations above version 11.5 the extension will be automatically installed. You do not have to activate it manually.

## Feature Flags

This document explains how to use feature flags. We separate between two different kinds of feature flags:

### Feature

Feature flags allow you to enable or disable specific features in your installation. \
This is of particular importance to disable features that have not passed the approval process.

### Optional

Optional flags allow you to (de-)activate specific features for your installation. \
A practical use for these is the (de-)activation of an extension.

For more information about feature flags in TYPO3, please refer to the official [TYPO3 Documentation on Feature Flags](https://docs.typo3.org/m/typo3/reference-coreapi/13.4/en-us/ApiOverview/FeatureToggleApi/Index.html).

### Feature Flag Configuration

Feature and Optional flags are configured in the `.env` or the `local-dev/.ddev/docker-compose.environment.yaml` file on ddev machine. To add a feature flag, use the following syntax:

```yaml
# FEATURE FLAG
- TYPO3__SYS__features__GSB11_FEATURE_123_NEW_FEATURE=%const(bool:true)%
# OPTIONAL FLAG
- TYPO3__SYS__features__GSB11_OPTION_123_ENABLE_EXTENSION=%const(bool:true)%
```

In this example, both feature flags, `GSB11_FEATURE_123_NEW_FEATURE` and `GSB11_OPTION_123_ENABLE_EXTENSION`,
are set to `true`. To disable the feature, change the value to `false` or delete the setting.

#### Feature Flag Truth Table

This table illustrates the behavior of feature flags in various states.

| Feature Flag State      | Evaluated Value | Description                              |
|-------------------------|-----------------|------------------------------------------|
| `featureFlag = true`    | `true`          | The feature is explicitly enabled.       |
| `featureFlag = false`   | `false`         | The feature is explicitly disabled.      |
| `featureFlag = ''`      | `false`         | An empty value is treated as `false`.    |
| `featureFlag not exist` | `false`         | A non-existent flag defaults to `false`. |

### Usage in PHP Code

To use a feature flag in your PHP code, you can check the flag's value with the `isFeatureEnabled()` method of the `Features` class:

```php
if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('GSB11_FEATURE_123_NEW_FEATURE')) {
    echo 'Feature is enabled';

    // Feature-specific code
    ...
}
```

### Usage in Fluid Templates

Feature flags can also be checked in your Fluid templates with TYPO3's [Feature ViewHelper](https://docs.typo3.org/other/typo3/view-helper-reference/13.4/en-us/Global/Feature.html).

#### Basic usage

```html
<f:feature name="GSB11_FEATURE_123_NEW_FEATURE">
   This is being shown if the flag is enabled
</f:feature>
```

#### Feature > then > else

```html
<f:feature name="GSB11_OPTION_123_ENABLE_EXTENSION">
    <f:then>
        Flag is enabled
    </f:then>
    <f:else>
        Flag is undefined or not enabled
    </f:else>
</f:feature>
```

### Current feature flags of `gsb_core`

| Feature flag           | Description                             |
|------------------------|-----------------------------------------|
| `GSB11_OPTION_1972_GSB11_BACKEND_BRANDING` | At default branding to the login screen |

## Usage

Nothing to do.

## Site package

Site packages in `EXT:gsb_core` are alike to [sets in TYPO3 13+][typo3-13-sets-url].

A package is available in the site configuration if it **matches one of the following criteria**:

* it's extension key **includes** `gsb_core` or `site` **AND does not include** `impexp`
* it **has** the following configuration in it's `composer.json`:

```json
    {
        "extra": {
            "itzbund/gsb-core": {
                 "isSitePackage": true
            }
        }
    }
```

If a package has been selected as a site package, it's typoscript configuration (`Configuration/TypoScript/{constants|setttings}.typoscript`) will be loaded as the root template, which allows for zero configuration deployments.

### Further Reading

For more information about feature flags in TYPO3, please refer to the [TYPO3 Documentation on Feature Flags](https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/Configuration/FeatureToggles.html).

## Middleware based endpoints

### `/api/version`

For infrastructure reasons this extension provides a version endpoint at `/api/version` which returns a json object with the following structure

```json
{
    "versions": {
        "gsb": "[string|null]",
        "container": "[string|null]",
        "helmChart": "[string|null]",
        "TYPO3": "[string|null]",
        "packageCacheHash": "[string|null]"
    }
}
```

This endpoint can be used to decide whether a cache flush might be necessary after a deployment (or: whether it's not - because the version hasn't changed).

### `/api/health`

This extension provides a health endpoint at `/api/health` which returns a response without a body and status code 204.

> ⚠️ **Warning**: on production systems you should restrict access to all `/api` paths from the outside to not expose critical system information.

## Contribute

As with TYPO3, we encourage you to join the project by submitting changes. Development of the GSB&nbsp;11 mainly happens in the GSB&nbsp;11 TYPO3 extension repositories.

To get started, have a look at our [detailed contribution walkthrough](https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/gitlab-profile/-/blob/main/CONTRIBUTING.md).

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[gsb11-readme-url]: https://gitlab.opencode.de/bmi/government-site-builder-11/extensions
[kickstarter-url]: https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/gsb-sitepackage-kickstarter
[typo3-13-sets-url]: [https:////](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/SiteHandling/SiteSets.html)
