import '../../Scss/interactiveImageFrontend.scss';
import { HotSpotViewer } from './utils/hotSpotViewer.js';

const mapHotSpots = (hotspots) => hotspots.filter(hotspot => hotspot.coordinates.length).map(hotspot => {
    const popupContainer = document.querySelector('#interactive-image-map-popups');
    return {
        tooltip: hotspot.tooltip,
        coordinates: JSON.parse(hotspot.coordinates),
        link: hotspot.link,
        content: popupContainer?.querySelector(`#c${hotspot.contents[0]?.uid}`),
    };
});

const init = () => {
    const interactiveImageMapContainer = document.querySelector('#interactive-image-map-container');
    if (!interactiveImageMapContainer) return;

    const imageUrl = interactiveImageMapContainer.getAttribute('data-image-url');
    const canvas = interactiveImageMapContainer.querySelector('canvas');
    const data = JSON.parse(interactiveImageMapContainer.getAttribute('data-data'));
    const hotspotsRaw = JSON.parse(interactiveImageMapContainer.getAttribute('data-hotspots'));
    const hotspots = mapHotSpots(hotspotsRaw, interactiveImageMapContainer);

    new HotSpotViewer({ canvas, imageUrl, hotspots, opts: {
        colorActive: data.color_active,
        colorDefault: data.color_default,
        colorHover: data.color_hover
    } });


};

window.addEventListener('DOMContentLoaded', init);
