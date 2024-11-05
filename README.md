<!--
SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

SPDX-License-Identifier: GPL-3.0-or-later
-->

<!-- PROJECT SHIELDS -->
[![TYPO3 12](https://img.shields.io/badge/TYPO3-12-orange.svg)](https://get.typo3.org/version/12)

# GSB&nbsp;11 Extension gsb_core


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

## Usage
Nothing to do.

## Feature Flags in `gsb_core`

This document explains how to use feature flags. Feature flags allow you to enable or disable specific features in your installation.
This is of particular importance to not use features that have not passed the approval process.

### Configuration

Feature flags are configured in the `.env` or the `local-dev/.ddev/docker-compose.environment.yaml` file on ddev machine. To add a feature flag, use the following syntax:

```plaintext
# Feature flag for the specific tickets. Set them to true to activate the features.
- TYPO3__SYS__features__ITZBUNDPHP-2877=%const(bool:true)%
```

In this example, the feature flag `ITZBUNDPHP-2877` is set to `true`. To disable the feature, change the value to `false` or delete
the setting.

#### Feature Flag Truth Table

This table illustrates the behavior of feature flags in various states.

| Feature Flag State      | Evaluated Value | Description                              |
|-------------------------|-----------------|------------------------------------------|
| `featureFlag = true`    | `true`          | The feature is explicitly enabled.       |
| `featureFlag = false`   | `false`         | The feature is explicitly disabled.      |
| `featureFlag = ''`      | `false`         | An empty value is treated as `false`.    |
| `featureFlag not exist` | `false`         | A non-existent flag defaults to `false`. |

### Curent feature flags of `gsb_core`

| Feature flag           | Description
|------------------------|------------------------------------------------------------------
| `brandingBackendLogin` | At default branding to the login screen
| `ITZBUNDPHP-2877`      | Enables general color management in the site module.<br />This sets the bootstrap colors `--bs-primary` `--bs-secondary` `--bs-tertiary` `--bs-quaternary`
| `ITZBUNDPHP-1615`      | Replaces the TYPO3 core default email template footer text with a GSB11 footer text
| `ITZBUNDPHP-2328`      | Streamline handling for linked pages in stage and singleteaser CEs. Fetches category and date value from target page and adds possibility to override/hide those values
| `ITZBUNDPHP-3327`      | Enable/Expose EXT:dpn_glossary in the TYPO3 backend
| `ITZBUNDPHP-3435`      | Enable generic video renderer to add support for external video sources<br />The allowed external sources have to be configured via the extension configuration: `allowedVideoDomains`
| `ITZBUNDPHP-3176`      | Enable Google Site Verification via SiteConfig
| `ITZBUNDPHP-3288`      | Enable i18n of logo, 2nd logo, initiative-text etc
| `ITZBUNDPHP-4070`      | Enable a feature that allows additional file extensions for the `uploads` element<br />These can be configured via the extension configuration: `additionalAllowedFileExtensionsForUploadsElement`

### Usage in PHP Code

To use a feature flag in your PHP code, you can check the flag's value in the global TYPO3 configuration. Here's an example:

```php
if (GeneralUtility::makeInstance(Features::class)->isFeatureEnabled('ITZBUNDPHP-2877')) {
    // Only if the feature flag is set to true the feature is activated
    // Feature-specific code goes here
}
```

In this example, the feature-specific code will only execute if the feature flag `ITZBUNDPHP-2877` is set to `true`.

### Usage in Fluid Templates

You can also use feature flags in your Fluid templates with a custom ViewHelper. First, ensure you include the namespace for the ViewHelper:

```plaintext
{namespace gsb=ITZBund\GsbCore\ViewHelpers}
```

Then, use the `featureFlag` ViewHelper to conditionally render content based on the feature flag:

```html
<f:if condition="{gsb:featureFlag(featureKey: 'ITZBUNDPHP-2877')}">
    <!-- Feature-specific content goes here -->
</f:if>
```

In this example, the content inside the `<f:if>` tag will always be rendered if the feature flag `ITZBUNDPHP-2877` is not set to `false`.

## Site package

Site packages in `EXT:gsb_core` are alike to [sets in TYPO3 13+][typo3-13-sets-url].

A package is available in the site configuration if it matches one of the following criteria:

* it's extension key includes `gsb_core` or `site` AND does not include `impexp`
* it includes the following configuration in it's `composer.json`:

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

## Special Endpoints

For infrastructure reasons this extension provides a version endpoint at **/api/version** which returns a json object with the following structure
```
{
    "versions": {
        "gsb": [string|null],
        "container": [string|null],
        "helmChart": [string|null],
        "TYPO3": "[string|null]",
        "packageCacheHash": "[string|null]"
    }
}
```

## Contribute
As with TYPO3, we encourage you to join the project by submitting changes. Development of the GSB&nbsp;11 mainly happens in the GSB&nbsp;11 TYPO3 extension repositories.

To get started, have a look at our [detailed contribution walkthrough](https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/gitlab-profile/-/blob/main/CONTRIBUTING.md).


<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[gsb11-readme-url]: https://gitlab.opencode.de/bmi/government-site-builder-11/extensions
[kickstarter-url]: https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/gsb_sitepackage
[typo3-13-sets-url]: [https:////](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/SiteHandling/SiteSets.html)

