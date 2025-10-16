/*
  SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

  SPDX-License-Identifier: GPL-3.0-or-later
*/

document.addEventListener("DOMContentLoaded", () => {
  const glossaryTriggers = document.querySelectorAll("dfn a.dpnglossary");
  const modalMap = new Map();
  let lastFocusedElement = null;

  function createBackdrop() {
    let existingBackdrop = document.querySelector(".modal-backdrop");
    if (!existingBackdrop) {
      const backdrop = document.createElement("div");
      backdrop.classList.add("modal-backdrop", "fade", "show");
      document.body.appendChild(backdrop);
      return backdrop;
    }
    return existingBackdrop;
  }

  function trapFocus(modal) {
    const focusableElements = Array.from(
      modal.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      )
    ).filter(el => !el.hasAttribute("disabled") && el.offsetParent !== null && !el.getAttribute("aria-hidden"));

    if (focusableElements.length === 0) return () => {};

    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];

    function handleTabKey(event) {
      if (event.key !== "Tab") return;
      if (event.shiftKey && document.activeElement === firstFocusable) {
        event.preventDefault();
        lastFocusable.focus();
      } else if (!event.shiftKey && document.activeElement === lastFocusable) {
        event.preventDefault();
        firstFocusable.focus();
      }
    }

    modal.addEventListener("keydown", handleTabKey);
    return () => modal.removeEventListener("keydown", handleTabKey);
  }

  function openModal(modal) {
    if (modalMap.get(modal)?.type === "vanillaOpen") return;

    lastFocusedElement = document.activeElement;

    const backdrop = createBackdrop();
    modal.classList.add("show");
    modal.style.display = "block";
    modal.removeAttribute("aria-hidden");
    document.body.classList.add("modal-open");

    const closeButton = modal.querySelector(".btn-close");
    if (closeButton) closeButton.focus();

    const removeFocusTrap = trapFocus(modal);

    const escHandler = (event) => {
      if (event.key === "Escape") closeModal();
    };
    document.addEventListener("keydown", escHandler);

    function closeModal() {
      modal.classList.remove("show");
      modal.setAttribute("aria-hidden", "true");
      modal.style.display = "none";
      console.log('Modal close');
      document.body.classList.remove("modal-open");

      if (backdrop && backdrop.parentNode) backdrop.parentNode.removeChild(backdrop);

      removeFocusTrap();
      document.removeEventListener("keydown", escHandler);
      if (closeButton) closeButton.removeEventListener("click", clickHandler);

      lastFocusedElement?.focus();
      modalMap.set(modal, { type: null });
    }

    const clickHandler = () => closeModal();
    if (closeButton) closeButton.addEventListener("click", clickHandler);
    backdrop.addEventListener("click", clickHandler);

    modalMap.set(modal, { type: "vanillaOpen" });
  }

  glossaryTriggers.forEach(trigger => {
    trigger.addEventListener("click", (event) => {
      event.preventDefault();

      const modal = document.querySelector(trigger.dataset.bsTarget || "#glossary-definition-modal");
      lastFocusedElement = trigger;

      const dfnElement = trigger.closest("dfn");

      const modalBody = modal.querySelector("#glossary-definition-modal-body");
      modalBody.innerHTML = dfnElement.dataset.glossaryText || "";
      console.log(dfnElement.dataset.glossaryText,'Text');

      const modalTitle = modal.querySelector("#glossary-definition-modal-title");
      modalTitle.textContent = dfnElement.dataset.glossaryTitle || "";

      const modalLink = modal.querySelector("#glossary-definition-modal-link");
      const href = trigger.getAttribute("href") || "#";
      modalLink.setAttribute("href", href);
      modalLink.classList.toggle("d-none", href === "#");

      const bootstrapAvailable = typeof bootstrap !== "undefined" && bootstrap.Modal;

      if (bootstrapAvailable) {
        if (!modalMap.has(modal) || modalMap.get(modal).type !== "bootstrap") {
          const bsInstance = new bootstrap.Modal(modal, { backdrop: "static", keyboard: false });
          modalMap.set(modal, { type: "bootstrap", instance: bsInstance });
        }

        const { instance } = modalMap.get(modal);
        instance.show();

        modal.addEventListener("shown.bs.modal", () => {
          const closeButton = modal.querySelector(".btn-close");
          if (closeButton) closeButton.focus();
        });

        modal.addEventListener("hidden.bs.modal", () => {
          lastFocusedElement?.focus();
        });
      } else {
        openModal(modal);
      }
    });
  });
});
