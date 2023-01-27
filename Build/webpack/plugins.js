/* global
    require, module
*/
const _StyleLintPlugin = require('stylelint-webpack-plugin')
const StyleLintPlugin = new _StyleLintPlugin({
  configFile: 'stylelint.config.js',
  context: 'Assets',
  files: '**/*.s?(a|c)ss',
  failOnError: true,
  emitErrors: true,
  fix: true
})

module.exports = {
  StyleLintPlugin: StyleLintPlugin
}
