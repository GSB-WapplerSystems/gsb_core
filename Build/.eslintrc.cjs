// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

/** @type {import('eslint').Linter.Config} */

module.exports = {
    ignorePatterns: ["eslint.config.js"],
    parserOptions: {
        ecmaVersion: "latest",
        sourceType: "module",
    },
    env: {
        browser: true,
        node: true
    },
    extends: ["eslint:recommended"],
    rules: {
        semi: "error",
        "prefer-const": "error",
        "no-undef": "warn",
    },
};
