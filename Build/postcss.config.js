// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/** @type {import('postcss').Config} */
export default {
  plugins: {
    // inline-svg
    'postcss-inline-svg': {},

    // svgo
    'postcss-svgo': {},

    // preset-env
    'postcss-preset-env': {
      browsers: 'last 1 Chrome version, not dead, fully supports es6'
    },

    // pxtorem
    'postcss-pxtorem': {
      rootValue: 16,
      propList: ['*']
    }
  }
};
