/*
  SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

  SPDX-License-Identifier: GPL-3.0-or-later
*/

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('dfn').forEach(function (el) {
    el.querySelector('a').addEventListener('click', event => {
      event.preventDefault();
    });
    el.addEventListener('click', function () {
      document.getElementById('glossary-definition-modal-link').href = el.querySelector('a').href || "#";
      document.getElementById('glossary-definition-modal-title').innerHTML = el.dataset.glossaryTitle || "";
      document.getElementById('glossary-definition-modal-body').innerHTML = el.dataset.glossaryText || "";
    });
  });
});
