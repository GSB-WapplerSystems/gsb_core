const params = require('./lib/params')
const formatAtRules = require('./lib/formatAtRules')
const formatOrder = require('./lib/formatOrder')
const formatRules = require('./lib/formatRules')
const formatComments = require('./lib/formatComments')
const formatSassVariables = require('./lib/formatSassVariables')

module.exports = function (options) {
  var paramer = params(options)
  return {
    postcssPlugin: 'stylefmt',
    Once: function Once (root, _ref) {
      var result = _ref.result

      return paramer(root, result).then(function (params) {
        if(params) {
          formatComments(root, params)
          formatAtRules(root, params)
          formatRules(root, params)
          formatSassVariables(root, params)
          // order should be the last to prevent empty line collapse in order rules
          formatOrder(root, params)
        }
      }).catch(function (err) {
        console.error(err.stack)
      })
    }
  }
}

module.exports.postcss = true
