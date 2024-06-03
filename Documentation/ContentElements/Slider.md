<!--
SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

SPDX-License-Identifier: GPL-3.0-or-later
-->

# Slider

The technical documentation for the slideshow is explained here.

- In the following line, the path to the slideshow is resolved:

```
Resources/Private/Templates/Container/Slider.html
```

- There we find a code snippet that points to our configuration in TypoScrip.

```
Configuration/TypoScript/Setup/ContentElements/tt_content/ContainerElements.typoscript
```

- The code snippet looks like this.

```
<f:format.raw>{child.renderedContent}</f:format.raw>
```

**To learn more about Fluid and TypoScript we refer to the TYPO3 documentation. It is important to use the correct documentation for the given TYPO3 version.**
