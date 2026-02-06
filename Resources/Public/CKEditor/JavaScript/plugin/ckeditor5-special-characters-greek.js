/**
 * CKEditor 5: toolbar button "Greek" to insert Greek characters.
 * Same pattern as TYPO3 Timestamp plugin (Plugin + ButtonView). Only ButtonView to avoid load errors.
 * SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

import { Plugin } from '@ckeditor/ckeditor5-core';

const GREEK_ITEMS = [
  { title: 'Alpha (Kleinbuchstabe)', character: 'α' },
  { title: 'Beta (Kleinbuchstabe)', character: 'β' },
  { title: 'Gamma (Kleinbuchstabe)', character: 'γ' },
  { title: 'Delta (Kleinbuchstabe)', character: 'δ' },
  { title: 'Epsilon (Kleinbuchstabe)', character: 'ε' },
  { title: 'Zeta (Kleinbuchstabe)', character: 'ζ' },
  { title: 'Eta (Kleinbuchstabe)', character: 'η' },
  { title: 'Theta (Kleinbuchstabe)', character: 'θ' },
  { title: 'Iota (Kleinbuchstabe)', character: 'ι' },
  { title: 'Kappa (Kleinbuchstabe)', character: 'κ' },
  { title: 'Lambda (Kleinbuchstabe)', character: 'λ' },
  { title: 'My (Kleinbuchstabe)', character: 'μ' },
  { title: 'Ny (Kleinbuchstabe)', character: 'ν' },
  { title: 'Xi (Kleinbuchstabe)', character: 'ξ' },
  { title: 'Omikron (Kleinbuchstabe)', character: 'ο' },
  { title: 'Pi (Kleinbuchstabe)', character: 'π' },
  { title: 'Rho (Kleinbuchstabe)', character: 'ρ' },
  { title: 'Sigma (Kleinbuchstabe)', character: 'σ' },
  { title: 'Tau (Kleinbuchstabe)', character: 'τ' },
  { title: 'Ypsilon (Kleinbuchstabe)', character: 'υ' },
  { title: 'Phi (Kleinbuchstabe)', character: 'φ' },
  { title: 'Chi (Kleinbuchstabe)', character: 'χ' },
  { title: 'Psi (Kleinbuchstabe)', character: 'ψ' },
  { title: 'Omega (Kleinbuchstabe)', character: 'ω' },
  { title: 'Alpha (Großbuchstabe)', character: 'Α' },
  { title: 'Beta (Großbuchstabe)', character: 'Β' },
  { title: 'Gamma (Großbuchstabe)', character: 'Γ' },
  { title: 'Delta (Großbuchstabe)', character: 'Δ' },
  { title: 'Epsilon (Großbuchstabe)', character: 'Ε' },
  { title: 'Zeta (Großbuchstabe)', character: 'Ζ' },
  { title: 'Eta (Großbuchstabe)', character: 'Η' },
  { title: 'Theta (Großbuchstabe)', character: 'Θ' },
  { title: 'Iota (Großbuchstabe)', character: 'Ι' },
  { title: 'Kappa (Großbuchstabe)', character: 'Κ' },
  { title: 'Lambda (Großbuchstabe)', character: 'Λ' },
  { title: 'My (Großbuchstabe)', character: 'Μ' },
  { title: 'Ny (Großbuchstabe)', character: 'Ν' },
  { title: 'Xi (Großbuchstabe)', character: 'Ξ' },
  { title: 'Omikron (Großbuchstabe)', character: 'Ο' },
  { title: 'Pi (Großbuchstabe)', character: 'Π' },
  { title: 'Rho (Großbuchstabe)', character: 'Ρ' },
  { title: 'Sigma (Großbuchstabe)', character: 'Σ' },
  { title: 'Tau (Großbuchstabe)', character: 'Τ' },
  { title: 'Ypsilon (Großbuchstabe)', character: 'Υ' },
  { title: 'Phi (Großbuchstabe)', character: 'Φ' },
  { title: 'Chi (Großbuchstabe)', character: 'Χ' },
  { title: 'Psi (Großbuchstabe)', character: 'Ψ' },
  { title: 'Omega (Großbuchstabe)', character: 'Ω' },
];

export class SpecialCharactersGreek extends Plugin {
  static get pluginName() {
    return 'specialCharactersGreek';
  }

  init() {
    console.log('SpecialCharactersGreek init');
    const editor = this.editor;
    const t = editor.t;
    const specialCharacters = editor.plugins.get('SpecialCharacters');
    specialCharacters.addItems('Greek', GREEK_ITEMS, { label: 'Griechisch' });
  }
}

export default SpecialCharactersGreek;
