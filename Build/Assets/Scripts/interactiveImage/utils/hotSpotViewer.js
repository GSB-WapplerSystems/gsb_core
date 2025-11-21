import { HotSpotCanvas } from './hotSpotCanvas.js';
import { Geometry } from './geometry.js';
//#region Type Definitions
/**
 * @typedef {Object} HotSpotViewerOptions
 * @property {boolean} [showPolygons]
 * @property {string} [fillColor]
 * @property {string} [strokeColor]
 * @property {string} [colorActive]
 * @property {string} [colorDefault]
 * @property {string} [colorHover]
 */

/**
 * @typedef {Object} HotSpotViewerHotSpot
 * @property {boolean} [showPolygons]
 * @property {string} [tooltip]
 * @property {string} [link]
 * @property {object} [coordinates]
 * @property {HTMLElement} [content]
 */

/**
 * @typedef {Object} HotSpotViewerParams
 * @property {HTMLCanvasElement} canvas
 * @property {string} imageUrl
 * @property {HotSpotViewerHotSpot[]} hotspots
 * @property {HotSpotViewerOptions} [opts]
 */
//#endregion


const BUTTON_DOT_RADIUS = 14;
const BUTTON_HEIGHT = 28;
const BUTTON_CENTER_Y = BUTTON_HEIGHT / 2;
const TOOLTIP_SPACING = 10;

export class HotSpotViewer extends HotSpotCanvas {

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
        this.colorActive = this._resolveColor(opts.colorActive) || '#22d3ee';
        this.colorDefault = this._resolveColor(opts.colorDefault) || '#22d3ee';
        this.colorHover = this._resolveColor(opts.colorHover) || '#22d3ee';

        this._setupCanvasAccessibility();
        this._createButtonContainer();
        this._createModal();
        this._waitForReadyAndApplyInit(this._applyInitialHotspots.bind(this));
        this._bindCanvasEvents();
    }

    _setupCanvasAccessibility() {
        this.canvas.setAttribute('tabindex', '0');
        this.canvas.setAttribute('role', 'application');
        this.canvas.setAttribute('aria-label', 'Interactive image map');
    }

    _ensureParentIsRelative(element) {
        const parent = element.parentElement;
        if (parent && getComputedStyle(parent).position === 'static') {
            parent.style.position = 'relative';
        }
    }

    _createTooltipElement(button, hotspot) {
        const tooltip = document.createElement('div');
        tooltip.className = 'hotspot-viewer-tooltip';
        tooltip.style.borderColor = this.colorDefault;
        tooltip.textContent = hotspot.tooltip || '';

        tooltip.addEventListener('click', (e) => {
            e.stopPropagation();
            if (hotspot.content) {
                this._openModal(hotspot.content);
            }
        });

        button.appendChild(tooltip);
        return tooltip;
    }

    _createButtonContainer() {
        this.buttonContainer = document.createElement('div');
        this.buttonContainer.className = 'hotspot-viewer-button-container';
        this._ensureParentIsRelative(this.canvas);
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
        dialog.setAttribute('tabindex', '-1');
        dialog.style.borderColor = this.colorDefault || '#22d3ee';
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
        closeButton.setAttribute('aria-label', 'Close modal');
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
        this.modalBackdrop = this._createModalBackdrop();
        this.modalDialog = this._createModalDialog();
        this.modalContent = this._createModalContent();
        this.modalCloseButton = this._createModalCloseButton();
        this.modalDialog.appendChild(this.modalCloseButton);
        this.modalDialog.appendChild(this.modalContent);
        this.modalBackdrop.appendChild(this.modalDialog);
        document.body.appendChild(this.modalBackdrop);
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
            content: hotspot.content || null,
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

    _createButtonElement(hotspot, index) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'hotspot-viewer-button';
        button.setAttribute('aria-label', hotspot.tooltip || `Hotspot ${index + 1}`);
        button.style.borderColor = this.colorHover;
        button.style.background = this.colorDefault;
        return button;
    }

    _createInnerDotElement() {
        const innerDot = document.createElement('span');
        innerDot.className = 'hotspot-viewer-button-inner-dot';
        innerDot.style.background = this.colorHover;
        return innerDot;
    }

    _attachButtonClickHandler(button, hotspot) {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            if (hotspot.content) {
                this._openModal(hotspot.content);
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

        this.hotspots.forEach((hotspot, index) => {
            if (!Array.isArray(hotspot.points) || hotspot.points.length === 0) return;

            const button = this._createButtonElement(hotspot, index);
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

    _updateButtonContainerSize(canvasRect) {
        this.buttonContainer.style.width = `${canvasRect.width}px`;
        this.buttonContainer.style.height = `${canvasRect.height}px`;
    }

    _calculateButtonPosition(hotspot, canvasRect, drawPoly) {
        const canvasPoints = this._normalizedToCanvas(hotspot.points, drawPoly);
        const centroid = Geometry.getPolygonCentroid(canvasPoints);
        if (!centroid) return null;

        const scaleX = canvasRect.width / this.canvas.width;
        const scaleY = canvasRect.height / this.canvas.height;

        return {
            x: centroid.x * scaleX,
            y: centroid.y * scaleY
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

    _shouldShowTooltip(hotspotIndex) {
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
            button.style.background = isActive ? this.colorHover : this.colorDefault;
            button.style.borderColor = isActive ? this.colorDefault : this.colorHover;
            innerDot.style.background = isActive ? this.colorDefault : this.colorHover;
        });
    }

    _isHotspotActive(hotspotIndex) {
        return this.hoveredHotspotIndex === hotspotIndex || this.focusedHotspotIndex === hotspotIndex;
    }

    _calculateButtonPositionForTooltip(hotspotIndex) {
        const canvasRect = this.canvas.getBoundingClientRect();
        const drawPoly = this._getCurrentDrawPoly();
        const hotspot = this.hotspots[hotspotIndex];
        const canvasPoints = this._normalizedToCanvas(hotspot.points, drawPoly);
        const centroid = Geometry.getPolygonCentroid(canvasPoints);
        if (!centroid) return null;

        const scaleX = canvasRect.width / this.canvas.width;
        return centroid.x * scaleX;
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
                tooltip.style.borderColor = this.colorDefault;
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

    _drawPolygons() {
        if (!this.showPolygons || !Array.isArray(this.hotspots)) return;
        const {
            ctx
        } = this;
        const drawPoly = this._getCurrentDrawPoly();
        ctx.save();

        this.hotspots.forEach(hotspot => {
            if (!Array.isArray(hotspot.points) || hotspot.points.length < 2) return;
            const canvasPoints = this._normalizedToCanvas(hotspot.points, drawPoly);
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

        ctx.fillStyle = this.colorDefault || '#22d3ee';
        ctx.fill();

        ctx.lineWidth = 2;
        ctx.strokeStyle = '#fff';
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.stroke();
    }

    _draw() {
        this._clear();
        this._drawBackground();
        if (this.showPolygons) this._drawPolygons();
        this._positionButtons();
    }

    _repositionModal() {
        // Modal now covers full page, no repositioning needed
    }

    _bindResizeHandlers() {
        const resizeObserver = new ResizeObserver(() => {
            this._positionButtons();
            this._repositionModal();
        });
        resizeObserver.observe(this.canvas);

        window.addEventListener('resize', () => {
            this._positionButtons();
            this._repositionModal();
        });
    }

    _bindCanvasEvents() {
        this.canvas.addEventListener('mousemove', (e) => this._onMouseMove(e));
        this.canvas.addEventListener('mouseleave', () => this._onMouseLeave());
        this.canvas.addEventListener('click', (e) => this._onCanvasClick(e));
        this._bindResizeHandlers();
    }

    _eventToCanvasCoords(e) {
        const cssPos = this._canvasPos(e);
        const dpr = Math.max(1, window.devicePixelRatio || 1);
        return {
            x: cssPos.x * dpr,
            y: cssPos.y * dpr
        };
    }

    _findHotspotAtPoint(x, y) {
        const drawPoly = this._getCurrentDrawPoly();
        for (let i = 0; i < this.hotspots.length; i++) {
            const hotspot = this.hotspots[i];
            if (!Array.isArray(hotspot.points) || hotspot.points.length < 3) continue;
            const canvasPoints = this._normalizedToCanvas(hotspot.points, drawPoly);
            if (Geometry.pointInPolygon(x, y, canvasPoints)) {
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
    }

    _onMouseLeave() {
        if (this.hoveredHotspotIndex !== -1) {
            this.hoveredHotspotIndex = -1;
            this._updateTooltipVisibility();
            this._draw();
        }
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
            if (hotspot.content) {
                this._openModal(hotspot.content);
            }
        }

        this._draw();
    }

    _setModalContent(contentElement) {
        this.modalContent.innerHTML = '';
        const clonedContent = contentElement.cloneNode(true);
        this.modalContent.appendChild(clonedContent);
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

    _openModal(contentElement) {
        if (!contentElement) return;

        this._setModalContent(contentElement);
        this.modalDialog.style.borderColor = this.colorDefault;
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

    _resolveColor(colorValue) {
        if (!colorValue) return null;

        if (colorValue.startsWith('#') || colorValue.startsWith('rgb') || colorValue.startsWith('hsl')) {
            return colorValue;
        }

        let cssVarName = colorValue;
        const colorMatch = colorValue.match(/^color_(\d+)$/);
        if (colorMatch) {
            cssVarName = `--bs-bgcolor-${colorMatch[1]}`;
        }

        if (!cssVarName.startsWith('--')) {
            return colorValue;
        }

        const element = this.canvas || document.documentElement;
        const computedValue = getComputedStyle(element).getPropertyValue(cssVarName).trim();
        return computedValue || colorValue;
    }

    _getCurrentDrawPoly() {
        return {
            dx: 0,
            dy: 0,
            dw: this.canvas.width,
            dh: this.canvas.height
        };
    }

    _normalizedToCanvas(points, drawPoly) {
        if (!drawPoly) return points;
        const {
            dx,
            dy,
            dw,
            dh
        } = drawPoly;
        return points.map(({
            u,
            v
        }) => ({
            x: dx + u * dw,
            y: dy + v * dh
        }));
    }
}
