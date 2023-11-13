# Content type help button

Info button can be added to content types.

## Configuration

in `$GLOBALS['TCA']['tt_content']['types']['CType']` a new option "infoButton" can be added to display info button.

infoButton Options:
- title (string required)
- link (string required)
- linkTarget (string optional. By default, this is set to "_blank"

## Example

```php
//gsb_core/Configuration/TCA/Overrides/tt_content_video.php

$GLOBALS['TCA']['tt_content']['types']['video']['infoButton'] = [
    'title' => 'this is video element',
    'link' => 'https://google.bg',
    'linkTarget' => '_blank',
];
```
