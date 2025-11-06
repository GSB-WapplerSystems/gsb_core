document.addEventListener('DOMContentLoaded', function () {
  const dsgvoContainers = document.querySelectorAll('.dsgvo-external');

  dsgvoContainers.forEach(container => {
    const textBlock = container.querySelector('.dsgvo-text');
    if (!textBlock) return;

    const overlay = document.createElement('div');
    overlay.classList.add('dsgvo-overlay');
    overlay.appendChild(textBlock);
    container.appendChild(overlay);

    const consentButton = overlay.querySelector('[data-dsgvo-button], .dsgvo-link, .dsgvo-accept');
    if (!consentButton) return;

    consentButton.addEventListener('click', function (event) {
      event.preventDefault();
      overlay.remove();
      activateExternalContent(container);
    });

    deactivateExternalContent(container);
  });

  function deactivateExternalContent(container) {
    const iframe = container.querySelector('iframe');
    if (iframe) {
      iframe.dataset.srcBackup = iframe.src;
      iframe.src = '';
    }

    const webComponent = container.querySelector('[data-url]');
    if (webComponent) {
      webComponent.dataset.urlBackup = webComponent.dataset.url;
      webComponent.dataset.url = '';
    }
  }

  function activateExternalContent(container) {
    const iframe = container.querySelector('iframe');
    if (iframe && iframe.dataset.srcBackup) {
      iframe.src = iframe.dataset.srcBackup;
    }

    const webComponent = container.querySelector('[data-url]');
    if (webComponent && webComponent.dataset.urlBackup) {
      webComponent.dataset.url = webComponent.dataset.urlBackup;
    }
  }
});
