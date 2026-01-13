import Modal from '@typo3/backend/modal.js';

window.GsbCoreHttpsUrlValidator = {
  showError(message) {
    Modal.confirm(
        'Fehlerhafte URL',
        message,
        Modal.types.alert,
        [
          {
            text: 'OK',
            btnClass: 'btn-primary',
            name: 'ok',
          },
        ]
    );
  },
};
