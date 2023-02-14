// screen.js

/**
 * Import just what you need out of bootstrap libs
 */

require('bootstrap/js/dist/base-component')
require('bootstrap/js/dist/alert')
require('bootstrap/js/dist/button')
require('bootstrap/js/dist/carousel')
require('bootstrap/js/dist/collapse')
require('bootstrap/js/dist/dropdown')
require('bootstrap/js/dist/modal')
// require('bootstrap/js/dist/offcanvas')
// require('bootstrap/js/dist/popover')
require('bootstrap/js/dist/scrollspy')
require('bootstrap/js/dist/tab')
// require('bootstrap/js/dist/toast')
// require('bootstrap/js/dist/tooltip')
/**
 * Including customized default javascript parts
 */
// require('./code/theme')
require('./code/mainNavigation')
require('./code/sticky')
require('./code/totop')
require('./code/modalGallery')
require('./code/resizeListener')
require('./code/jarallax')
// require('./code/masonry')
require('./code/main')

/**
 * Include stylesheets
 */
require('../Scss/screen.scss')
