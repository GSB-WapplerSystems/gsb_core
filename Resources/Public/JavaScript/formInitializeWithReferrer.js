/*
  SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

  SPDX-License-Identifier: GPL-3.0-or-later
*/
document.addEventListener("DOMContentLoaded", (event) => {
  let referrer = document.referrer || false;
  if (referrer !== false) {
      document.querySelectorAll("[data-initialize-with-referrer='1']").forEach((field) => {
          field.value = referrer;
      });
  }
});
