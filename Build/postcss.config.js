module.exports = {
  plugins: {
    // inline-svg
    '@andreyvolokitin/postcss-inline-svg': {},

    // preset-env
    'postcss-preset-env': {
      browsers: 'defaults'
    },

    // pxtorem
    'postcss-pxtorem': {
      rootValue: 16,
      propList: ['*']
    }
  }
}
