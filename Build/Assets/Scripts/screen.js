// screen.js

/**
 * Import just what you need out of bootstrap libs
 */
window.bootstrap = require('bootstrap')

/**
 * Including customized default javascript parts
 */
require('./code/mainNavigation')
require('./code/mainNavigationDesktop')
require('./code/modalGallery')
require('./code/resizeListener')
require('./code/scrollTo')
require('./code/sticky')
require('./code/totop')
require('./code/main')

/**
 * Include stylesheets
 */
require('../Scss/screen.scss')
