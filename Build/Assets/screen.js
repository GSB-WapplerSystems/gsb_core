// screen.js

/**
 * Including javascript parts from packages
 */
window.bootstrap = require('bootstrap')

/**
 * Including customized default javascript parts
 */
require('./Scripts/checkOS')
require('./Scripts/mainNavigation')
require('./Scripts/totop')
require('./Scripts/modalGallery')
require('./Scripts/resizeListener')
require('./Scripts/scrollTo')
require('./Scripts/gsb_info')
require('./Scripts/main')

/**
 * Include stylesheets
 */
require('./Scss/screen.scss')
