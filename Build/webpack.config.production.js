// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

import CopyPlugin from 'copy-webpack-plugin';
import ESLintPlugin from 'eslint-webpack-plugin';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import path from 'path';
import StyleLintPlugin from 'stylelint-webpack-plugin';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

export default {
  // Define the entry points of our application (can be multiple for different sections of a website)
  entry: {
    'interactiveImage/backend': './Assets/Scripts/interactiveImage/backend.js',
    'interactiveImage/frontend': './Assets/Scripts/interactiveImage/frontend.js',
    'validation/formValidationAutofocus': './Assets/Scripts/validation/formValidationAutofocus.js',
    'validation/httpsUrlValidator': './Assets/Scripts/validation/httpsUrlValidator.js',
    dsgvoExternalLoader: './Assets/Scripts/dsgvoExternalLoader.js',
    formInitializeWithReferrer: './Assets/Scripts/formInitializeWithReferrer.js'
  },

  // Define the destination directory and filenames of compiled resources and files
  output: {
    filename: 'JavaScript/[name].js',
    path: path.resolve(__dirname, '../Resources/Public'),
    assetModuleFilename: '[name][ext]',
    clean: true
  },

  experiments: { outputModule: true },
  externalsType: 'module',
  externals: [/^@typo3\//],

  // Define loaders
  module: {
    rules: [
      // CSS, PostCSS, and Sass
      {
        test: /\.(scss|css)$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              importLoaders: 2,
              sourceMap: false,
              url: true
            }
          },
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: [
                  'autoprefixer'
                ]
              }
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sassOptions: {
                silenceDeprecations: ['color-functions', 'global-builtin', 'import'],
              }
            }
          }
        ]
      },
      {
        test: [/\.bmp$/, /\.gif$/, /\.jpe?g$/, /\.png$/, /\.svg$/],
        type: 'asset/resource',
        generator: {
          filename: 'Images/[name][ext]'
        }
      },
      {
        test: [/\.woff$/, /\.woff2$/],
        type: 'asset/resource',
        generator: {
          filename: 'Fonts/[name][ext]'
        }
      }
    ]
  },

  // Define used plugins
  plugins: [
    new ESLintPlugin({
      failOnError: true
    }),

    new StyleLintPlugin({
      configFile: 'stylelint.config.js',
      context: 'Assets',
      files: '**/*.s?(a|c)ss',
      failOnError: true,
      emitErrors: true,
      fix: true
    }),

    new CopyPlugin({
      patterns: [
        {from: './Assets/Static', to: './'}
      ]
    }),

    // Extracts CSS into separate files
    new MiniCssExtractPlugin({
      filename: 'StyleSheets/[name].css',
      chunkFilename: '[id].css'
    })
  ]
};
