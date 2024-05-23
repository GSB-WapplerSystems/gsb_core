<!--
SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

SPDX-License-Identifier: GPL-3.0-or-later
-->

# Content element information field

Additional information can be added to existing content elements.

## Configuration

New fieldInformation type was created "elementInformationText" witch takes texts array to display information.

Texts field options :
- text (string required: this can be simple text or translation string)
- bold (optional: boolean)
- italic (optional: boolean)
- link (string optional: this will make the text a link)
- linkTarget (string optional: this is the link target. By default, this is set to "_blank")

## Example

```php
//EXT:gsb_core/Configuration/TCA/Overrides/tt_content_video.php

'tx_video_caption' =>
    [
        'config' =>
            [
                //...
                'fieldInformation' => [
                    'elementInformationText' => [
                        'renderType' => 'elementInformationText',
                        'options' => [
                            'texts' => [
                                [
                                    'text' => 'LLL:EXT:gsb_core/Resources/Private/Language/locallang_db.xlf:tt_content.tx_video_caption',
                                    'bold' => true,
                                ],
                                [
                                    'text' => 'Simple text',
                                    'italic' => true,
                                ],
                                [
                                    'text' => 'Link text',
                                    'link' => 'http://google.com',
                                    'linkTarget' => '_self'
                                ],
                            ],
                        ],
                    ],
                ],
                //...
            ],
        //...
    ],
```
