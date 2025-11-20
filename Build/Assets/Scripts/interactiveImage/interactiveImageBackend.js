
import Modal from '@typo3/backend/modal.js';
import DocumentService from '@typo3/core/document-service.js';
import RegularEvent from '@typo3/core/event/regular-event.js';
import '../../Scss/interactiveImageBackend.scss';

import { HotSpotSelector } from './utils/hotSpotSelector.js';

// https://docs.typo3.org/m/typo3/reference-coreapi/13.4/en-us/ApiOverview/Backend/JavaScript/Index.html

const init = () => {
    const hotspotContainer = document.querySelector('[data-local-field="hotspot"]');
    if (!hotspotContainer) return;

    const showModal = (name, coordinatesField, imageUrl) => {
        const mount = document.createElement('div');
        mount.className = 'f';
        mount.id = 'my-react-root';
        mount.innerHTML = '<h3>This is my Image tool!</h3>';
        const canvas = document.createElement('canvas');

        mount.appendChild(canvas);

        let currentCoordinates = null;

        const updateCoordinates = (coordinates) => {
            currentCoordinates = JSON.stringify(coordinates);

        };

        const modalEl = Modal.advanced({
            title: `Hotspot bearbeiten: ${name}`,
            content: mount,
            size: Modal.sizes.full,
            staticBackdrop: true,
            buttons: [
                { text: 'Löschen', btnClass: 'btn-default', name: 'clear' },
                { text: 'Schließen', trigger: (_e, modalElement) => modalElement.hideModal(), btnClass: 'btn-default' },
                {
                    text: 'Speichern',
                    btnClass: 'btn-primary',
                    name: 'confirm',
                    trigger: (_e, modalElement) => {
                        coordinatesField.value = currentCoordinates;
                        // Notification.success(`Koordinaten von "${name}" wurden übertragen`);
                        modalElement.hideModal();
                    },
                },
            ],
        });

        modalEl.addEventListener('shown.bs.modal', () => {
            const clearBtn = modalEl.querySelector('button[name="clear"]');
            new HotSpotSelector({
                canvas,
                imageUrl,
                ui: { clearBtn },
                onShapeChange: updateCoordinates,
                opts: { mode: 'rect', minRectSize: 50 },
                initPoints: coordinatesField.value ? JSON.parse(coordinatesField.value) : null });
        }, { once: true });
    };

    new RegularEvent('click', function (e) {
        console.log('click event');
        const activePanel = e.target.closest('.panel');
        const coordinatesField = activePanel?.querySelector('[name*="[coordinates]"]');
        const dataLink = activePanel?.querySelector('.hotspot.btn');
        const hotspotTitle = dataLink?.dataset.tooltip || 'Unbenannt';
        const imageUrl = dataLink?.dataset.image && `${window.location.protocol}//${window.location.host}/fileadmin${dataLink?.dataset.image}`;
        if (!coordinatesField || !imageUrl) return;
        showModal(hotspotTitle, coordinatesField, imageUrl);
    }).delegateTo(hotspotContainer, '.hotspot.btn');
};

DocumentService.ready().then(() => {
    init();
});
