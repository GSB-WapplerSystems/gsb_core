// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/** @type {import('stylelint').Config} */
export default {
  extends: [
    'stylelint-config-recommended',
    'stylelint-config-recommended-scss',
    'stylelint-config-standard'
  ],
  plugins: [
    'stylelint-scss',
    'stylelint-order'
  ],
  ignoreFiles: ['**/*.html', '**/*.js', '**/*.php'],
  rules: {
    'at-rule-empty-line-before': [
      'always',
      {
        except: ['first-nested', 'blockless-after-blockless'],
        ignore: 'after-comment'
      }
    ],
    'at-rule-no-unknown': null,
    'scss/at-rule-no-unknown': true,
    'declaration-block-no-shorthand-property-overrides': true,
    'declaration-empty-line-before': null,
    'font-family-no-missing-generic-family-keyword': true,
    'no-descending-specificity': null,
    'order/properties-alphabetical-order': null,
    'selector-type-no-unknown': [true, {severity: 'warning'}],
    'selector-class-pattern': null,
    'scss/no-global-function-names': null,
    'declaration-block-no-redundant-longhand-properties': null,
    'function-url-quotes': null,
    'function-no-unknown': null,
    'declaration-property-value-no-unknown': null,
    'at-rule-descriptor-value-no-unknown': null,
    'property-no-vendor-prefix': [
      true,
      {
        ignoreProperties: ['backface-visibility', 'appearance']
      }
    ],
    'selector-id-pattern': null,
    'import-notation': null
  }
};
