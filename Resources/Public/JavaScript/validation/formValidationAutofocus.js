document.addEventListener('DOMContentLoaded', (event) => {
  setTimeout(() => {
    document.querySelector('main form [aria-invalid="true"]')?.focus();
  }, 0);
});
