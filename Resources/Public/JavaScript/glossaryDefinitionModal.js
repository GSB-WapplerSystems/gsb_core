/*
  SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

  SPDX-License-Identifier: GPL-3.0-or-later
*/

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('dfn').forEach(function (el) {
    if (el.querySelector('a.dpnglossary') !== null) {
      el.querySelector('a.dpnglossary').addEventListener('click', event => {
        event.preventDefault();
      });
      el.addEventListener('click', function () {
        document.getElementById('glossary-definition-modal-title').innerHTML = el.dataset.glossaryTitle || "";
        document.getElementById('glossary-definition-modal-body').innerHTML = el.dataset.glossaryText || "";
        document.getElementById('glossary-definition-modal-link').setAttribute('href', el.querySelector('a.dpnglossary').getAttribute('href') || "#");
        if (document.getElementById('glossary-definition-modal-link').getAttribute('href') == '#') {
          document.getElementById('glossary-definition-modal-link').classList.add('d-none');
        } else {
          document.getElementById('glossary-definition-modal-link').classList.remove('d-none');
        }
      });
    }
  });
});
