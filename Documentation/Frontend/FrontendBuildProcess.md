# Frontend-Build

## Frontend-Build meta information

### The most important packages
- The processes for the frontend build take place in the ./Build/ folder
- Node Version 18 LTS is used for the work https://nodejs.org/en/
- For development the latest bootstrap library is used https://getbootstrap.com/
- Webpack is used to compile the frontend https://webpack.js.org/
- Two important linters are worked with in this project. The ESlint and stylelint are used.
More details can be found in the package.json
- In addition postcss is also used to implement routine operations for the SCSS/CSS https://postcss.org/

### Some node packages are special and must be taken into account during implementation.
- The dependencies media element is included in order to. This is an audio and video player library for people with disabilities,
such as blindness or other impairments.
- In addition, there is the package swiper for the presentation of a slideshow.
Here too, the theme is simplified development for people with disabilities.

### The path to be able to develop
1. Go to the level of the **gsb_core/Build** folder
2. Install node js
   ```sh
   npm ci
   ```
3. Check the package.json in the build folder to see which scripts you can use to assemble the js and the SCSS/CSS.
There are different possibilities.
An example of the compiled files for the product system looks like this:
   ```sh
   npm run build
   ```
4. After the example above has been executed, a folder is created under */Resources/Public/* with all relevant files.
The following folders should be there.

BackendLayouts, CKEditor, Favicons, Fonts, Forms,
Icons, Images, avaScript, Seo, StyleSheets**
