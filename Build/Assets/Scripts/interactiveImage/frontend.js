// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

// interactiveImage/frontend.js

import '../../Scss/interactiveImage/frontend.scss';
import { HotSpotViewer } from './utils/hotSpotViewer.js';

const mapHotSpots = (hotspots) => hotspots.filter(hotspot => hotspot.coordinates.length).map(hotspot => {
    const popupContainer = document.querySelector('#interactive-image-map-popups');
    return {
        tooltip: hotspot.tooltip,
        coordinates: JSON.parse(hotspot.coordinates),
        link: {
            url: hotspot.urlParts.typolink,
            target: hotspot.urlParts.target,
            title: hotspot.urlParts.title,
        },
        contents: hotspot.contents.map(content => popupContainer?.querySelector(`#c${content?.uid}`)),
    };
});

const init = () => {
    const interactiveImageMapContainer = document.querySelector('#interactive-image-map-container');
    if (!interactiveImageMapContainer) return;

    const imageUrl = interactiveImageMapContainer.getAttribute('data-image-url');
    const imageAlt = interactiveImageMapContainer.getAttribute('data-image-alt');
    const imageDescription = interactiveImageMapContainer.getAttribute('data-image-description');
    const canvas = interactiveImageMapContainer.querySelector('canvas');
    const hotspotsRaw = JSON.parse(interactiveImageMapContainer.getAttribute('data-hotspots'));
    const hotspots = mapHotSpots(hotspotsRaw, interactiveImageMapContainer);

    new HotSpotViewer({ canvas, imageUrl, hotspots, opts: { imageAlt, imageDescription }});


};

window.addEventListener('DOMContentLoaded', init);
