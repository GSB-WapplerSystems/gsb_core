document.addEventListener('DOMContentLoaded', function () {
  const dsgvoContainers = document.querySelectorAll('.dsgvo-external');

  dsgvoContainers.forEach(container => {
    const textBlock = container.querySelector('.dsgvo-text');
    if (!textBlock) return;

    const overlay = document.createElement('div');
    overlay.classList.add('dsgvo-overlay');

    const info = document.createElement('div');
    info.innerHTML = `
            <p><strong>Externe Inhalte blockiert</strong></p>
            <p>Mit einem Klick auf "Inhalt laden" stimmen Sie der Übermittlung Ihrer Daten
            an externe Dienste (z. B. YouTube) gemäß Artikel 49 Abs. 1 lit. a DSGVO zu.</p>
        `;

    const button = document.createElement('button');
    button.textContent = 'Inhalt laden';
    button.classList.add('btn', 'btn-primary', 'dsgvo-accept');

    overlay.appendChild(info);
    overlay.appendChild(button);

    container.appendChild(overlay);

    button.addEventListener('click', function () {
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
