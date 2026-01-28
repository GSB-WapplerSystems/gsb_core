// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

// hotSpotViewer.js

import { HotSpotCanvas } from './hotSpotCanvas.js';
import { hotspotHelper } from './hotspotHelper.js';

/**
 * @typedef {Object} HotSpotViewerOptions
 * @property {boolean} [showPolygons]
 * @property {string} [imageAlt]
 * @property {string} [imageDescription]
 */

/**
 * @typedef {Object} HotSpotLink
 * @property {string} [url]
 * @property {string} [target]
 * @property {string} [title]
 */

/**
 * @typedef {Object} HotSpotViewerHotSpot
 * @property {boolean} [showPolygons]
 * @property {string} [tooltip]
 * @property {HotSpotLink} [link]
 * @property {object} [coordinates]
 * @property {HTMLElement[]} [contents]
 */

/**
 * @typedef {Object} HotSpotViewerParams
 * @property {HTMLCanvasElement} canvas
 * @property {string} imageUrl
 * @property {HotSpotViewerHotSpot[]} hotspots
 * @property {HotSpotViewerOptions} [opts]
 */

const BUTTON_DOT_RADIUS = 14;
const BUTTON_HEIGHT = 28;
const BUTTON_CENTER_Y = BUTTON_HEIGHT / 2;
const TOOLTIP_SPACING = 10;
const CSS_COLOR_TERTIARY = 'var(--bs-tertiary)';
const CSS_COLOR_SECONDARY = 'var(--bs-secondary)';

export class HotSpotViewer extends HotSpotCanvas {

    /**
     * @param {HotSpotViewerParams} params
     */
    constructor({
        canvas,
        imageUrl,
        hotspots,
        opts = {}
    }) {
        super({
            canvas,
            imageUrl
        });

        this.hotspotsRaw = hotspots;
        this.hotspots = [];
        this._pendingInit = true;
        this.hoveredHotspotIndex = -1;
        this.focusedHotspotIndex = -1;
        this.showPolygons = Boolean(opts.showPolygons);
        this.imageAlt = opts.imageAlt || 'Interactive image map';
        this.imageDescription = opts.imageDescription || '';

        this._setupCanvasAccessibility();
        this._createButtonContainer();
        this._createModal();
        this._waitForReadyAndApplyInit(this._applyInitialHotspots.bind(this));
        this._bindCanvasEvents();
    }

    _setupCanvasAccessibility() {
        this.canvas.setAttribute('role', 'img');
        this.canvas.setAttribute('aria-label', this.imageAlt);
        this.canvas.setAttribute('aria-description', this.imageDescription);
    }

    _createButtonContainer() {
        this.buttonContainer = document.createElement('div');
        this.buttonContainer.className = 'hotspot-viewer-button-container';
        this.canvas.parentElement.insertBefore(this.buttonContainer, this.canvas.nextSibling);
        this.hotspotButtons = [];
    }

    _createModalBackdrop() {
        const backdrop = document.createElement('div');
        backdrop.className = 'hotspot-viewer-modal-backdrop';
        this._attachModalBackdropEvents(backdrop);
        return backdrop;
    }

    _createModalDialog() {
        const dialog = document.createElement('div');
        dialog.className = 'hotspot-viewer-modal-dialog';
        dialog.setAttribute('role', 'dialog');
        dialog.setAttribute('aria-modal', 'true');
        dialog.setAttribute('aria-label', 'Hotspot content');
        dialog.setAttribute('tabindex', '-1');
        return dialog;
    }

    _createModalContent() {
        const content = document.createElement('div');
        content.className = 'hotspot-viewer-modal-content';
        return content;
    }

    _createModalCloseButton() {
        const closeButton = document.createElement('button');
        closeButton.className = 'hotspot-viewer-modal-close';
        closeButton.setAttribute('aria-label', 'Close hotspot content modal');
        closeButton.setAttribute('type', 'button');
        closeButton.innerHTML = '×';
        closeButton.addEventListener('click', () => this._closeModal());
        return closeButton;
    }

    _attachModalBackdropEvents(backdrop) {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                this._closeModal();
            }
        });

        backdrop.addEventListener('wheel', (e) => {
            if (e.target === backdrop) {
                e.preventDefault();
            }
        }, {
            passive: false
        });

        backdrop.addEventListener('touchmove', (e) => {
            if (e.target === backdrop) {
                e.preventDefault();
            }
        }, {
            passive: false
        });

        backdrop.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this._closeModal();
            }
        });
    }

    _createModal() {
        const container = document.getElementById('interactive-image-map-container');
        this.modalBackdrop = this._createModalBackdrop();
        this.modalDialog = this._createModalDialog();
        this.modalContent = this._createModalContent();
        this.modalCloseButton = this._createModalCloseButton();
        this.modalDialog.appendChild(this.modalCloseButton);
        this.modalDialog.appendChild(this.modalContent);
        this.modalBackdrop.appendChild(this.modalDialog);
        container.appendChild(this.modalBackdrop);
        this._focusableElements = [];
        this._firstFocusableElement = null;
        this._lastFocusableElement = null;
    }

    _applyInitialHotspots() {
        if (!this._pendingInit) return;
        if (!Array.isArray(this.hotspotsRaw)) return;

        this.hotspots = this.hotspotsRaw.map(hotspot => ({
            tooltip: hotspot.tooltip || '',
            link: hotspot.link || '',
            points: hotspot.coordinates?.normalized || [],
            contents: Array.isArray(hotspot.contents) ? hotspot.contents : [],
        }));

        this._pendingInit = false;
        this._createHotspotButtons();
        this._draw();
    }

    _clearExistingButtons() {
        this.hotspotButtons.forEach(({
            button
        }) => button.remove());
        this.hotspotButtons = [];
    }

    _createButtonElement(hotspot, index, totalCount) {
        const buttonNumber = index + 1;
        const ariaLabel = hotspot.tooltip
            ? `${hotspot.tooltip}, hotspot ${buttonNumber} of ${totalCount}`
            : `Hotspot ${buttonNumber} of ${totalCount}`;

        if (hotspot.link?.url) {
            const link = document.createElement('a');
            link.href = hotspot.link.url;
            link.className = 'hotspot-viewer-button';
            link.setAttribute('aria-label', ariaLabel);

            if (hotspot.link.target) {
                link.target = hotspot.link.target;
            }

            if (hotspot.link.title) {
                link.title = hotspot.link.title;
            }

            return link;
        }
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'hotspot-viewer-button';
        button.setAttribute('aria-label', ariaLabel);
        return button;
    }

    _createInnerDotElement() {
        const innerDot = document.createElement('span');
        innerDot.className = 'hotspot-viewer-button-inner-dot';
        return innerDot;
    }

    _attachButtonClickHandler(button, hotspot) {
        if (button.tagName !== 'BUTTON') return;

        button.addEventListener('click', (e) => {
            e.stopPropagation();
            if (Array.isArray(hotspot.contents) && hotspot.contents.length > 0) {
                this._openModal(hotspot.contents);
            }
        });
    }

    _attachButtonHoverHandlers(button, index) {
        button.addEventListener('mouseenter', () => {
            this.hoveredHotspotIndex = index;
            this._updateButtonStates();
            this._updateTooltipVisibility();
        });

        button.addEventListener('mouseleave', () => {
            if (this.hoveredHotspotIndex === index) {
                this.hoveredHotspotIndex = -1;
                this._updateButtonStates();
                this._updateTooltipVisibility();
            }
        });
    }

    _attachButtonFocusHandlers(button, index) {
        button.addEventListener('focus', () => {
            this.focusedHotspotIndex = index;
            this._updateButtonStates();
            this._updateTooltipVisibility();
        });

        button.addEventListener('blur', () => {
            if (this.focusedHotspotIndex === index) {
                this.focusedHotspotIndex = -1;
                this._updateButtonStates();
                this._updateTooltipVisibility();
            }
        });
    }

    _createHotspotButtons() {
        this._clearExistingButtons();

        const validHotspots = this.hotspots.filter(hotspot =>
            Array.isArray(hotspot.points) && hotspot.points.length > 0
        );
        const totalCount = validHotspots.length;
        let validIndex = 0;

        this.hotspots.forEach((hotspot, index) => {
            if (!Array.isArray(hotspot.points) || hotspot.points.length === 0) return;

            validIndex++;
            const button = this._createButtonElement(hotspot, validIndex, totalCount);
            const innerDot = this._createInnerDotElement();
            button.appendChild(innerDot);

            const tooltip = hotspot.tooltip ? this._createTooltipElement(button, hotspot) : null;

            this._attachButtonClickHandler(button, hotspot);
            this._attachButtonHoverHandlers(button, index);
            this._attachButtonFocusHandlers(button, index);

            this.buttonContainer.appendChild(button);
            this.hotspotButtons.push({
                button,
                innerDot,
                tooltip,
                hotspotIndex: index
            });
        });

        this._positionButtons();
    }

    _createTooltipElement(button, hotspot) {
        const tooltip = document.createElement('div');
        tooltip.className = 'hotspot-viewer-tooltip';
        tooltip.textContent = hotspot.tooltip || '';
        button.appendChild(tooltip);
        return tooltip;
    }

    _bindResizeHandlers() {
        const resizeObserver = new ResizeObserver(() => {
            this._positionButtons();
        });
        resizeObserver.observe(this.canvas);

        window.addEventListener('resize', () => {
            this._positionButtons();
        });
    }

    _bindCanvasEvents() {
        this.canvas.addEventListener('mousemove', (e) => this._onMouseMove(e));
        this.canvas.addEventListener('mouseleave', () => this._onMouseLeave());
        this.canvas.addEventListener('click', (e) => this._onCanvasClick(e));
        this._bindResizeHandlers();
    }

    _draw() {
        this._clear();
        this._drawBackground();
        if (this.showPolygons) this._drawPolygons();
        this._positionButtons();
    }

    _drawPolygons() {
        if (!this.showPolygons || !Array.isArray(this.hotspots)) return;
        const {
            ctx
        } = this;
        const drawPoly = this._getCurrentDrawPoly();
        ctx.save();

        this.hotspots.forEach(hotspot => {
            if (!Array.isArray(hotspot.points) || hotspot.points.length < 2) return;
            const canvasPoints = hotspotHelper.normalizedToCanvas(hotspot.points, drawPoly);
            this._drawSinglePolygon(ctx, canvasPoints);
        });

        ctx.restore();
    }

    _drawSinglePolygon(ctx, canvasPoints) {
        ctx.beginPath();
        ctx.moveTo(canvasPoints[0].x, canvasPoints[0].y);
        for (let i = 1; i < canvasPoints.length; i++) {
            ctx.lineTo(canvasPoints[i].x, canvasPoints[i].y);
        }
        ctx.closePath();

        const fillColor = hotspotHelper.resolveColor(CSS_COLOR_SECONDARY);
        ctx.fillStyle = fillColor;
        ctx.fill();

        ctx.lineWidth = 2;
        const strokeColor = hotspotHelper.resolveColor(CSS_COLOR_TERTIARY);
        ctx.strokeStyle = strokeColor;
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.stroke();
    }

    _onMouseMove(e) {
        const {
            x,
            y
        } = this._eventToCanvasCoords(e);
        const newHoveredIndex = this._findHotspotAtPoint(x, y);

        if (this.hoveredHotspotIndex !== newHoveredIndex) {
            this.hoveredHotspotIndex = newHoveredIndex;
            this._clearFocus();
            this._updateButtonStates();
            this._updateTooltipVisibility();
            this._draw();
        }

        this.canvas.style.cursor = newHoveredIndex !== -1 ? 'pointer' : 'default';
    }

    _onMouseLeave() {
        if (this.hoveredHotspotIndex !== -1) {
            this.hoveredHotspotIndex = -1;
            this._updateTooltipVisibility();
            this._draw();
        }
        this.canvas.style.cursor = 'default';
    }

    _onCanvasClick(e) {
        const {
            x,
            y
        } = this._eventToCanvasCoords(e);
        this._clearFocus();

        const hotspotIndex = this._findHotspotAtPoint(x, y);
        if (hotspotIndex !== -1) {
            const hotspot = this.hotspots[hotspotIndex];
            if (hotspot.link?.url) {
                if (hotspot.link.target === '_blank') {
                    window.open(hotspot.link.url, hotspot.link.target);
                } else {
                    window.location.href = hotspot.link.url;
                }
            } else if (Array.isArray(hotspot.contents) && hotspot.contents.length > 0) {
                this._openModal(hotspot.contents);
            }
        }

        this._draw();
    }

    _openModal(contents) {
        if (!Array.isArray(contents) || contents.length === 0) return;

        this._setModalContent(contents);
        this._disableInteractions();
        this._preventBodyScroll();
        this.modalBackdrop.style.display = 'flex';
        this.modalDialog.focus();
    }

    _closeModal() {
        this.modalBackdrop.style.display = 'none';
        this.modalContent.innerHTML = '';
        this._enableInteractions();
        this._allowBodyScroll();
    }

    _updateButtonContainerSize(canvasRect) {
        this.buttonContainer.style.width = `${canvasRect.width}px`;
        this.buttonContainer.style.height = `${canvasRect.height}px`;
    }

    _calculateButtonPosition(hotspot, canvasRect, drawPoly) {
        const canvasPoints = hotspotHelper.normalizedToCanvas(hotspot.points, drawPoly);
        const centroid = hotspotHelper.getPolygonCentroid(canvasPoints);
        if (!centroid) return null;

        return {
            x: centroid.x,
            y: centroid.y
        };
    }

    _positionSingleButton(buttonData, canvasRect, drawPoly) {
        const {
            button,
            tooltip,
            hotspotIndex
        } = buttonData;
        const hotspot = this.hotspots[hotspotIndex];

        if (!Array.isArray(hotspot.points) || hotspot.points.length === 0) {
            button.style.display = 'none';
            return;
        }

        const position = this._calculateButtonPosition(hotspot, canvasRect, drawPoly);
        if (!position) {
            button.style.display = 'none';
            return;
        }

        button.style.left = `${position.x}px`;
        button.style.top = `${position.y}px`;
        button.style.display = 'flex';

        if (tooltip && this._shouldShowTooltip(hotspotIndex)) {
            this._positionTooltipRelativeToButton(tooltip, position.x, canvasRect.width);
        }
    }

    _isMobileDevice() {
        return 'ontouchstart' in window || window.matchMedia('(max-width: 62rem)').matches;
    }

    _shouldShowTooltip(hotspotIndex) {
        if (this._isMobileDevice()) {
            return false;
        }
        return this.hoveredHotspotIndex === hotspotIndex || this.focusedHotspotIndex === hotspotIndex;
    }

    _positionButtons() {
        if (!this.buttonContainer || !this.hotspots) return;

        const canvasRect = this.canvas.getBoundingClientRect();
        const drawPoly = this._getCurrentDrawPoly();

        this._updateButtonContainerSize(canvasRect);
        this.hotspotButtons.forEach(buttonData => {
            this._positionSingleButton(buttonData, canvasRect, drawPoly);
        });
    }

    _measureTooltipDimensions(tooltip) {
        const originalDisplay = tooltip.style.display;
        tooltip.style.visibility = 'hidden';
        tooltip.style.display = 'block';
        const rect = tooltip.getBoundingClientRect();
        tooltip.style.display = originalDisplay || 'none';
        tooltip.style.visibility = 'visible';
        return {
            width: rect.width,
            height: rect.height
        };
    }

    _calculateTooltipHorizontalPosition(buttonX, tooltipWidth, containerWidth) {
        const buttonRightEdge = buttonX + BUTTON_DOT_RADIUS;
        const buttonLeftEdge = buttonX - BUTTON_DOT_RADIUS;
        const spaceOnRight = containerWidth - buttonRightEdge - TOOLTIP_SPACING;
        const spaceOnLeft = buttonLeftEdge - TOOLTIP_SPACING;

        const rightPlacementX = BUTTON_DOT_RADIUS + (TOOLTIP_SPACING * 2);
        const leftPlacementX = -BUTTON_DOT_RADIUS - TOOLTIP_SPACING - tooltipWidth;

        let tooltipX;
        let needsClamping = false;

        if (spaceOnRight >= tooltipWidth) {
            tooltipX = rightPlacementX;
        } else if (spaceOnLeft >= tooltipWidth) {
            tooltipX = leftPlacementX;
        } else {
            tooltipX = spaceOnRight > spaceOnLeft ? rightPlacementX : leftPlacementX;
            needsClamping = true;
        }

        if (needsClamping) {
            const absoluteX = buttonX + tooltipX;
            if (absoluteX < 0) {
                tooltipX = Math.max(0, leftPlacementX);
            } else if (absoluteX + tooltipWidth > containerWidth) {
                tooltipX = containerWidth - buttonX - tooltipWidth;
            }
        }

        return tooltipX;
    }

    _calculateTooltipVerticalPosition(tooltipHeight) {
        return BUTTON_CENTER_Y - (tooltipHeight / 2);
    }

    _positionTooltipRelativeToButton(tooltip, buttonX, containerWidth) {
        const {
            width: tooltipWidth,
            height: tooltipHeight
        } = this._measureTooltipDimensions(tooltip);
        const tooltipX = this._calculateTooltipHorizontalPosition(buttonX, tooltipWidth, containerWidth);
        const tooltipY = this._calculateTooltipVerticalPosition(tooltipHeight);

        tooltip.style.left = `${tooltipX}px`;
        tooltip.style.top = `${tooltipY}px`;
    }

    _updateButtonStates() {
        this.hotspotButtons.forEach(({
            button,
            innerDot,
            hotspotIndex
        }) => {
            const isActive = this._isHotspotActive(hotspotIndex);
            button.style.background = isActive ? CSS_COLOR_TERTIARY : CSS_COLOR_SECONDARY;
            button.style.borderColor = isActive ? CSS_COLOR_SECONDARY : CSS_COLOR_TERTIARY;
            innerDot.style.background = isActive ? CSS_COLOR_SECONDARY : CSS_COLOR_TERTIARY;
        });
    }

    _isHotspotActive(hotspotIndex) {
        return this.hoveredHotspotIndex === hotspotIndex || this.focusedHotspotIndex === hotspotIndex;
    }

    _calculateButtonPositionForTooltip(hotspotIndex) {
        const drawPoly = this._getCurrentDrawPoly();
        const hotspot = this.hotspots[hotspotIndex];
        const canvasPoints = hotspotHelper.normalizedToCanvas(hotspot.points, drawPoly);
        const centroid = hotspotHelper.getPolygonCentroid(canvasPoints);
        if (!centroid) return null;

        return centroid.x;
    }

    _updateTooltipVisibility() {
        this.hotspotButtons.forEach(({
            tooltip,
            hotspotIndex
        }) => {
            if (!tooltip) return;

            const shouldShow = this._shouldShowTooltip(hotspotIndex);

            if (shouldShow) {
                tooltip.style.display = 'block';
                const buttonX = this._calculateButtonPositionForTooltip(hotspotIndex);
                if (buttonX !== null) {
                    const canvasRect = this.canvas.getBoundingClientRect();
                    this._positionTooltipRelativeToButton(tooltip, buttonX, canvasRect.width);
                }
            } else {
                tooltip.style.display = 'none';
            }
        });
    }

    _eventToCanvasCoords(e) {
        const cssPos = this._canvasPos(e);
        return {
            x: cssPos.x,
            y: cssPos.y
        };
    }

    _findHotspotAtPoint(x, y) {
        const drawPoly = this._getCurrentDrawPoly();
        for (let i = 0; i < this.hotspots.length; i++) {
            const hotspot = this.hotspots[i];
            if (!Array.isArray(hotspot.points) || hotspot.points.length < 3) continue;
            const canvasPoints = hotspotHelper.normalizedToCanvas(hotspot.points, drawPoly);
            if (hotspotHelper.pointInPolygon(x, y, canvasPoints)) {
                return i;
            }
        }
        return -1;
    }

    _clearFocus() {
        if (this.focusedHotspotIndex !== -1) {
            this.focusedHotspotIndex = -1;
        }
    }

    _setModalContent(contents) {
        this.modalContent.innerHTML = '';
        contents.forEach(contentElement => {
            if (contentElement && contentElement.cloneNode) {
                const clonedContent = contentElement.cloneNode(true);
                this.modalContent.appendChild(clonedContent);
            }
        });
    }

    _disableInteractions() {
        this.hotspotButtons.forEach(({
            button
        }) => {
            button.style.pointerEvents = 'none';
        });
        this.buttonContainer.style.pointerEvents = 'none';
        this.canvas.style.pointerEvents = 'none';
    }

    _enableInteractions() {
        this.hotspotButtons.forEach(({
            button
        }) => {
            button.style.pointerEvents = 'auto';
        });
        this.canvas.style.pointerEvents = 'auto';
    }

    _preventBodyScroll() {
        document.body.style.overflow = 'hidden';
    }

    _allowBodyScroll() {
        document.body.style.overflow = '';
    }

    _getCurrentDrawPoly() {
        const canvasRect = this.canvas.getBoundingClientRect();
        return {
            dx: 0,
            dy: 0,
            dw: canvasRect.width,
            dh: canvasRect.height
        };
    }
}
