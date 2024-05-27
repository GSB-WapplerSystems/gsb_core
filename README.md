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


## Contribute
As with TYPO3, we encourage you to join the project by submitting changes. Development of the GSB&nbsp;11 mainly happens in the GSB&nbsp;11 TYPO3 extension repositories.

To get started, have a look at our [detailed contribution walkthrough](https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/gitlab-profile/-/blob/main/CONTRIBUTING.md).


<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[gsb11-readme-url]: https://gitlab.opencode.de/bmi/government-site-builder-11/extensions
[kickstarter-url]: https://gitlab.opencode.de/bmi/government-site-builder-11/extensions/gsb_sitepackage
