## Migrating TYPO3 project/extension into webpack process

### Fonts

- Move files from *Resources/Public/**Fonts**/* into *Build/Assets/**Fonts**/*

### Images

- Move files from *Resources/Public/**Images**/* into *Build/Assets/**Images**/

  Images (like SVG, GIF, PNG, etc.), that are stored in *Build/Assets/Images/*, will used in SCSS files to compile into CSS. With a different use-statements the images will copy into *Resources/Public/Images/* directly or convert into base64 context.

### Scripts

- Move files from *Resources/Public/**JavaScript**/* into *Build/Assets/**Scripts**/*

### SCSS

- Move sources from folder *Resources/Private/Design/**Scss**/* into *Build/Assets/**Scss**/*

### Static files to copy one-on-one into public folder

- Move directory **BackendLayouts** from *Resources/Public* into *Build/Assets/Static*

- Move directory **Images** (like favicon, logo, static images, etc.) from *Resources/Public/Images* into *Build/Assets/Static*

```
Build/
  Assets/
    Static/
      BackendLayouts/
      Images/
        Icons/
        favicon.ico
        ...
      ...
```

------

### Defining build process

#### <u>Build/webpack.config.js</u>

```javascript
Encore
// ...

/*
 * ENTRY CONFIG
 *
 * Add 1 entry for each "page" of your app
 * (including one that"s included on every page - e.g. "app")
 *
 * Each entry will result in one JavaScript file (e.g. app.js)
 * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
 */
.createSharedEntry("vendor", _config[_project.type].paths.assets + "/vendor")
//.addEntry("screen", _config[_project.type].paths.assets + "/screen")

// ...
;
```

To add a new entry to compile SCSS into CSS or compile javascript their are following steps to do:

- [ ] Create new javascript file into *Build/Assets/* like *Build/Assets/**screen.js*** to compile SCSS into CSS

- [ ] Add new Entry into webpack.config.js

  ```javascript
  Encore
  // ...
  
  /*
   * ENTRY CONFIG
   *
   * Add 1 entry for each "page" of your app
   * (including one that"s included on every page - e.g. "app")
   *
   * Each entry will result in one JavaScript file (e.g. app.js)
   * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
   */
  .createSharedEntry("vendor", _config[_project.type].paths.assets + "/vendor")
  .addEntry("screen", _config[_project.type].paths.assets + "/screen")
  
  // ...
  ;
  ```

- [ ] Require files to compile into new created javascript file (like Build/Assets/screen.js)

  ```javascript
  /*
  global
      require
  */
  
  // screen.js
  require("./Scss/screen.scss");
  
  ```

#### <u>Using images/fonts in new structured SCSS files</u>

- Using images with **url(...)**

  Define relative path back to source file, for example:

  ```
  Build/
    Assets/
      Images/
        Icons/
          trenner.png
      Scss/
        Elements/
          _icons.scss
  ```

  Defintion into **_icons.scss**

  ```scss
  @charset "utf-8";
  
  // ...
  
  .metanav {
    li {
      &:not(:first-child) {
        background: url('../../Images/Icons/trenner.png') no-repeat 0 10px;
      }
    }
  }
  
  // ...
  ```

  url() using initial path from file where its called. In that case the url include as a background is to go back two-times from initial path to reference the png file.

- Using fonts

  ```
  Build/
    Assets/
      Fonts/
        fontawesome-webfont.woff
      Scss/
        Base/
          _fonts.scss
  ```

  Defintion into **_fonts.scss**

  ```scss
  @include font-face(
    'FontAwesome',
    '../../Fonts/fontawesome-webfont',
    400,
    normal,
    woff2 woff
  );
  
  // ...
  ```

  font-face() using initial path from file where its called. In that case the fonts include is to go back two-times from initial path to reference the file.

- Using **svg-load(...)**

  ```
  Build/
    Assets/
      Images/
        Icons/
          mejs-controls.svg
      Scss/
        Modules/
          _mediaelement.scss
        screen.scss
  ```

  Defintion into **_mediaelement.scss**

  ```scss
  // ...
  
  .mejs__overlay-button,
  .mejs__overlay-loading-bg-img,
  .mejs__button > button {
    background-image: svg-load('../Images/Icons/icon_mejs-controls.svg');
  }
  
  // ...
  ```

  svg-load() using initial path from @import statement defined in screen.scss. In that case the svg include as a background-image is to go back one-time from initial path to reference the svg file.
