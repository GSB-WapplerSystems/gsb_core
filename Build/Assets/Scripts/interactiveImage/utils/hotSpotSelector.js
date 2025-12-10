// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

// hotSpotSelector.js

import { HotSpotCanvas } from './hotSpotCanvas.js';
import { hotspotHelper } from './hotspotHelper.js';

/**
 * @typedef {Object} HotSpotSelectorOptions
 * @property {'rect' | 'polygon'} [mode]
 * @property {number} [minRectSize]
 * @property {boolean} [showDefaultRec]
 */

/**
 * @typedef {Object} Ui
 * @property {HTMLButtonElement} [clearBtn]
 */

/**
 * @typedef {Object} HotSpotSelectorParams
 * @property {HTMLCanvasElement} canvas
 * @property {string} imageUrl
 * @property {Ui} [ui]
 * @property {Function} [onShapeChange]
 * @property {HotSpotSelectorOptions} [opts]
 * @property {Array} [initPoints]
 */

const FILL_COLOR = 'rgba(30, 58, 138, 0.25)';
const STROKE_COLOR = '#fff';
const POINT_COLOR = '#000';
const PREVIEW_COLOR = '#38bdf8';

export class HotSpotSelector extends HotSpotCanvas {

    /**
     * @param {HotSpotSelectorParams} params
     */
    constructor({canvas, imageUrl, ui, onShapeChange, opts = {}, initPoints }){
        super({canvas, imageUrl});

        this.ui = ui || {};
        this.onShapeChange = typeof onShapeChange === 'function' ? onShapeChange : () => {};

        this.points = [];
        this.mode = opts.mode === 'rect' ? 'rect' : 'polygon';
        this.isShapeClosed = false;
        this.isDrawingRectangle = false;
        this.rectangleStartPoint = null;
        this.rectangleEndPoint = null;
        this.draggedPointIndex = -1;
        this.hoveredPointIndex = -1;
        this.isPanningShape = false;
        this.panningLastPosition = null;

        this.minimumRectangleSize = (opts && Number.isFinite(opts.minRectSize)) ? opts.minRectSize : 10;
        this.pointRadius = 4;
        this.pointCloseThreshold = 10;
        this.showDefaultRectangle = Boolean(opts.showDefaultRec);

        this._bindUI();
        this._bindCanvasEvents();
        this.canvas.style.cursor = 'crosshair';

        this._pendingInit = initPoints || null;
        this._waitForReadyAndApplyInit(this._applyInitialPoints.bind(this));
    }

    setMode(mode){
        const normalizedMode = mode === 'rect' ? 'rect' : 'polygon';
        if (this.mode === normalizedMode) return;
        this.mode = normalizedMode;
        this.isShapeClosed = false;
        this.isDrawingRectangle = false;
        this.rectangleStartPoint = null;
        this.rectangleEndPoint = null;
        this.points = [];
        this._draw();
    }

    _applyInitialPoints() {
        if (this._hasNormalizedInitialPoints()) {
            this._applyNormalizedPoints();
            return;
        }

        if (this._shouldCreateDefaultRectangle()) {
            this._createDefaultRectangle();
        }
    }

    _hasNormalizedInitialPoints() {
        return this._pendingInit
            && this._pendingInit.normalized
            && this._pendingInit.basis
            && this._pendingInit.basis.drawRect;
    }

    _applyNormalizedPoints() {
        const { normalized } = this._pendingInit;
        const currentDrawRect = this._getCurrentDrawRect();
        if (!currentDrawRect) return;

        const { dx, dy, dw, dh } = currentDrawRect;
        this.points = normalized.map(({u, v}) => ({
            x: dx + u * dw,
            y: dy + v * dh
        }));
        this.isShapeClosed = this.points.length >= 3;
        this._draw();
    }

    _shouldCreateDefaultRectangle() {
        return this.showDefaultRectangle && !this._pendingInit;
    }

    _createDefaultRectangle() {
        const currentDrawRect = this._getCurrentDrawRect();
        if (!currentDrawRect) return;

        const centerX = currentDrawRect.dx + currentDrawRect.dw / 2;
        const centerY = currentDrawRect.dy + currentDrawRect.dh / 2;
        const defaultRectangleSize = 100;
        const halfSize = defaultRectangleSize / 2;

        const topLeftX = centerX - halfSize;
        const topLeftY = centerY - halfSize;
        const bottomRightX = centerX + halfSize;
        const bottomRightY = centerY + halfSize;

        this.points = [
            { x: topLeftX, y: topLeftY },
            { x: bottomRightX, y: topLeftY },
            { x: bottomRightX, y: bottomRightY },
            { x: topLeftX, y: bottomRightY }
        ];
        this.isShapeClosed = true;
        this._draw();
        this._emitShape();
    }

    _bindUI(){
        const { clearBtn } = this.ui;
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this._clearAllPoints());
        }
    }

    _bindCanvasEvents(){
        this.canvas.addEventListener('click', (e) => this._onCanvasClick(e));
        this.canvas.addEventListener('contextmenu', (e) => this._onContextMenu(e));
        this.canvas.addEventListener('mousemove', (e) => this._onMouseMove(e));
        this.canvas.addEventListener('mousedown', (e) => this._onMouseDown(e));
        this.canvas.addEventListener('mouseup', () => this._onMouseUp());
        this.canvas.addEventListener('mouseleave', () => this._onMouseLeave());
    }

    _handleResize(){
        super._handleResize(this._scalePointsByFactor.bind(this));
    }

    _draw(){
        this._clear();
        this._drawBackground();

        if (this._shouldDrawRectanglePreview()) {
            this._drawRectanglePreview(this.rectangleStartPoint, this.rectangleEndPoint);
        }

        if (this.isShapeClosed) {
            this._drawClosedShape();
        } else {
            this._drawOpenShape();
        }
    }

    _shouldDrawRectanglePreview() {
        return this.mode === 'rect'
            && this.isDrawingRectangle
            && this.rectangleStartPoint
            && this.rectangleEndPoint;
    }

    _drawClosedShape() {
        this._drawPath(true);
        this._drawPoints();
        this._drawCentroid();
    }

    _drawOpenShape() {
        this._drawPath(false);
        this._drawPoints();
        this._drawFirstPointCloseHint();
    }

    _drawPoints(){
        if(!this.points || this.points.length === 0) return;
        const { ctx } = this;
        ctx.save();
        ctx.fillStyle = POINT_COLOR;
        ctx.strokeStyle = STROKE_COLOR;

        this.points.forEach((point) => {
            ctx.beginPath();
            ctx.arc(point.x, point.y, this.pointRadius, 0, Math.PI * 2);
            ctx.fill();
            ctx.stroke();
        });

        ctx.restore();
    }

    _drawPath(shouldClose = true){
        if(!this.points || this.points.length < 2) return;

        const { ctx } = this;
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(this.points[0].x, this.points[0].y);

        for (let i = 1; i < this.points.length; i++) {
            ctx.lineTo(this.points[i].x, this.points[i].y);
        }

        if (shouldClose) {
            ctx.closePath();
        }

        if (shouldClose && this.isShapeClosed) {
            ctx.fillStyle = FILL_COLOR;
            ctx.fill();
        }

        ctx.lineWidth = 2;
        ctx.strokeStyle = STROKE_COLOR;
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.stroke();
        ctx.restore();
    }

    _drawCentroid(){
        if (!this.isShapeClosed || this.points.length < 2) return;

        const centroid = this._calculatePolygonCentroid();
        if (!centroid) return;

        const { ctx } = this;
        const centroidRadius = 5;
        ctx.save();
        ctx.fillStyle = STROKE_COLOR;
        ctx.beginPath();
        ctx.arc(centroid.x, centroid.y, centroidRadius, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }

    _drawFirstPointCloseHint(){
        if (this.isShapeClosed) return;
        if (this.mode !== 'polygon') return;
        if (this.points.length < 3) return;

        const firstPoint = this.points[0];
        const { ctx } = this;
        const hintRadius = this.pointRadius + 8;

        ctx.save();
        ctx.beginPath();
        ctx.lineWidth = 2;
        ctx.setLineDash([4, 4]);
        ctx.strokeStyle = STROKE_COLOR;
        ctx.arc(firstPoint.x, firstPoint.y, hintRadius, 0, Math.PI * 2);
        ctx.stroke();
        ctx.restore();
    }

    _drawRectanglePreview(startPoint, endPoint){
        const minX = Math.min(startPoint.x, endPoint.x);
        const minY = Math.min(startPoint.y, endPoint.y);
        const maxX = Math.max(startPoint.x, endPoint.x);
        const maxY = Math.max(startPoint.y, endPoint.y);
        const width = maxX - minX;
        const height = maxY - minY;

        const { ctx } = this;
        ctx.save();
        ctx.setLineDash([6, 6]);
        ctx.lineWidth = 2;
        ctx.strokeStyle = PREVIEW_COLOR;
        ctx.strokeRect(minX, minY, width, height);
        ctx.restore();
    }

    _onCanvasClick(e){
        if (this.isShapeClosed) return;
        if (this.mode === 'rect') return;

        const {x, y} = this._canvasPos(e);

        if (this._shouldClosePolygonOnFirstPoint(x, y)) {
            this._closePolygon();
            return;
        }

        this.points.push({x, y});
        this._draw();
    }

    _shouldClosePolygonOnFirstPoint(x, y) {
        if (this.points.length < 3) return false;

        const nearestPointIndex = this._findNearestPointIndex(x, y);
        return nearestPointIndex === 0;
    }

    _closePolygon() {
        this.isShapeClosed = true;
        this._draw();
        this._emitShape();
    }

    _onContextMenu(e){
        e.preventDefault();
        if (this.mode === 'rect' && this.isShapeClosed) return;
        if (!this.points || this.points.length === 0) return;

        const {x, y} = this._canvasPos(e);
        const pointIndexToRemove = this._findClosestPointIndex(x, y);

        if (pointIndexToRemove !== -1) {
            this.points.splice(pointIndexToRemove, 1);
        }

        if (this.points.length < 2) {
            this.isShapeClosed = false;
        }
        this._draw();
    }

    _onMouseMove(e){
        const {x, y} = this._canvasPos(e);

        if (this._handleRectanglePreviewMode(x, y)) return;
        if (this._handlePolygonCloseHintMode(x, y)) return;
        if (!this.isShapeClosed) {
            this.canvas.style.cursor = 'crosshair';
            return;
        }

        if (this._handleShapePanning(x, y)) return;
        if (this._handleVertexDragging(x, y)) return;

        this._updateCursorForHover(x, y);
    }

    _handleRectanglePreviewMode(x, y) {
        if (this.isShapeClosed || this.mode !== 'rect') return false;

        if (this.isDrawingRectangle && this.rectangleStartPoint) {
            this.rectangleEndPoint = this._ensureMinimumRectangleSize(
                this.rectangleStartPoint,
                { x, y }
            );
            this._draw();
        }
        this.canvas.style.cursor = 'crosshair';
        return true;
    }

    _handlePolygonCloseHintMode(x, y) {
        if (this.isShapeClosed || this.mode !== 'polygon') return false;

        if (this.points.length >= 3) {
            const nearestPointIndex = this._findNearestPointIndex(x, y);
            if (nearestPointIndex === 0) {
                this.canvas.style.cursor = 'pointer';
                return true;
            }
        }
        this.canvas.style.cursor = 'crosshair';
        return true;
    }

    _handleShapePanning(x, y) {
        if (!this.isPanningShape || !this.panningLastPosition) return false;

        const deltaX = x - this.panningLastPosition.x;
        const deltaY = y - this.panningLastPosition.y;

        for (let i = 0; i < this.points.length; i++){
            this.points[i].x += deltaX;
            this.points[i].y += deltaY;
        }

        this.panningLastPosition = {x, y};
        this.canvas.style.cursor = 'grabbing';
        this._draw();
        return true;
    }

    _handleVertexDragging(x, y) {
        if (this.draggedPointIndex === -1) return false;

        if (this.mode === 'rect' && this.isShapeClosed) {
            this._updateRectangleCornersDuringDrag(this.draggedPointIndex, x, y);
        } else {
            this.points[this.draggedPointIndex].x = x;
            this.points[this.draggedPointIndex].y = y;
        }

        this._draw();
        return true;
    }

    _updateCursorForHover(x, y) {
        this.hoveredPointIndex = this._findNearestPointIndex(x, y);

        if (this.hoveredPointIndex !== -1) {
            this.canvas.style.cursor = 'pointer';
            return;
        }

        this.canvas.style.cursor = this._isPointInsidePolygon(x, y) ? 'grab' : 'default';
    }

    _onMouseDown(e){
        const {x, y} = this._canvasPos(e);

        if (this._startRectangleDrawing(x, y, e)) return;
        if (!this.isShapeClosed) return;

        if (this._startVertexDrag(x, y, e)) return;
        if (this._startShapePan(x, y, e)) return;
    }

    _startRectangleDrawing(x, y, event) {
        if (this.isShapeClosed || this.mode !== 'rect') return false;

        this.isDrawingRectangle = true;
        this.rectangleStartPoint = {x, y};
        this.rectangleEndPoint = {x, y};
        event.preventDefault();
        this._draw();
        return true;
    }

    _startVertexDrag(x, y, event) {
        const nearestPointIndex = this._findNearestPointIndex(x, y);
        if (nearestPointIndex === -1) return false;

        this.draggedPointIndex = nearestPointIndex;
        this.canvas.style.cursor = 'grabbing';
        event.preventDefault();
        return true;
    }

    _startShapePan(x, y, event) {
        if (!this._isPointInsidePolygon(x, y)) return false;

        this.isPanningShape = true;
        this.panningLastPosition = {x, y};
        this.canvas.style.cursor = 'grabbing';
        event.preventDefault();
        return true;
    }

    _onMouseUp(){
        if (this._finalizeRectangleDrawing()) return;

        const wasDraggingVertex = this.draggedPointIndex !== -1;
        const wasPanningShape = this.isPanningShape;

        this._endVertexDrag();
        this._endShapePan();

        if (this.isShapeClosed && (wasDraggingVertex || wasPanningShape)) {
            this._emitShape();
        }
    }

    _finalizeRectangleDrawing() {
        if (this.isShapeClosed || this.mode !== 'rect') return false;
        if (!this.isDrawingRectangle) return false;
        if (!this.rectangleStartPoint || !this.rectangleEndPoint) return false;

        this._finalizeRectangleFromPoints(this.rectangleStartPoint, this.rectangleEndPoint);
        return true;
    }

    _endVertexDrag() {
        if (this.draggedPointIndex !== -1) {
            this.draggedPointIndex = -1;
            this._draw();
        }
    }

    _endShapePan() {
        if (this.isPanningShape) {
            this.isPanningShape = false;
            this.panningLastPosition = null;
        }
    }

    _onMouseLeave(){
        this._endVertexDrag();
        this._endShapePan();
        this.hoveredPointIndex = -1;
        this.canvas.style.cursor = this.isShapeClosed ? 'default' : 'crosshair';
    }

    _getCurrentDrawRect(){
        return {
            dx: 0,
            dy: 0,
            dw: this.canvas.width,
            dh: this.canvas.height
        };
    }

    _getShapeData() {
        const canvasSpacePoints = this._calculateCanvasSpacePoints();
        const canvasSpaceCenter = this._calculateCanvasSpaceCenter();
        const normalizedData = this._calculateNormalizedData(canvasSpacePoints, canvasSpaceCenter);

        return this._createShapeDataObject(canvasSpacePoints, canvasSpaceCenter, normalizedData);
    }

    _calculateCanvasSpacePoints() {
        return this.points.map(point => ({
            x: Math.round(point.x * 1000) / 1000,
            y: Math.round(point.y * 1000) / 1000
        }));
    }

    _calculateCanvasSpaceCenter() {
        const centroid = this._calculatePolygonCentroid() || { x: null, y: null };
        return {
            x: centroid && centroid.x != null ? Math.round(centroid.x * 1000) / 1000 : null,
            y: centroid && centroid.y != null ? Math.round(centroid.y * 1000) / 1000 : null
        };
    }

    _calculateNormalizedData(canvasSpacePoints, canvasSpaceCenter) {
        const drawRect = this._getCurrentDrawRect();
        if (!drawRect) return { points: null, center: null };

        const { dx, dy, dw, dh } = drawRect;
        const convertToNormalized = (point) => ({
            u: (point.x - dx) / dw,
            v: (point.y - dy) / dh
        });

        const normalizedPoints = canvasSpacePoints.map(convertToNormalized);
        const normalizedCenter = canvasSpaceCenter.x == null
            ? null
            : convertToNormalized(canvasSpaceCenter);

        return { points: normalizedPoints, center: normalizedCenter };
    }

    _createShapeDataObject(canvasSpacePoints, canvasSpaceCenter, normalizedData) {
        const drawRect = this._getCurrentDrawRect();
        return {
            points: canvasSpacePoints,
            center: canvasSpaceCenter,
            normalized: normalizedData.points,
            normalizedCenter: normalizedData.center,
            basis: drawRect ? {
                drawRect,
                fit: 'contain',
                image: {
                    w: this.bgImage.naturalWidth,
                    h: this.bgImage.naturalHeight
                },
                canvas: {
                    w: this.canvas.width,
                    h: this.canvas.height
                }
            } : null
        };
    }

    _emitShape() {
        if (!this.isShapeClosed) return;
        this.onShapeChange(this._getShapeData());
    }

    _findNearestPointIndex(x, y, threshold = this.pointCloseThreshold){
        return hotspotHelper.nearestPoint(x, y, this.points, threshold);
    }

    _isPointInsidePolygon(x, y){
        return hotspotHelper.pointInPolygon(x, y, this.points);
    }

    _calculatePolygonCentroid(){
        return hotspotHelper.getPolygonCentroid(this.points);
    }

    _clearAllPoints(){
        this.points = [];
        this.isShapeClosed = false;
        this.isDrawingRectangle = false;
        this.rectangleStartPoint = null;
        this.rectangleEndPoint = null;
        this._draw();
    }

    _finalizeRectangleFromPoints(startPoint, endPoint){
        const clampedEndPoint = this._ensureMinimumRectangleSize(startPoint, endPoint);
        const rectangleBounds = this._calculateRectangleBounds(startPoint, clampedEndPoint);
        this._setRectanglePoints(rectangleBounds);
        this._resetRectangleDrawingState();
        this._draw();
        this._emitShape();
    }

    _calculateRectangleBounds(startPoint, endPoint) {
        return {
            topLeftX: Math.min(startPoint.x, endPoint.x),
            topLeftY: Math.min(startPoint.y, endPoint.y),
            bottomRightX: Math.max(startPoint.x, endPoint.x),
            bottomRightY: Math.max(startPoint.y, endPoint.y)
        };
    }

    _setRectanglePoints(bounds) {
        this.points = [
            { x: bounds.topLeftX, y: bounds.topLeftY },
            { x: bounds.bottomRightX, y: bounds.topLeftY },
            { x: bounds.bottomRightX, y: bounds.bottomRightY },
            { x: bounds.topLeftX, y: bounds.bottomRightY }
        ];
        this.isShapeClosed = true;
    }

    _resetRectangleDrawingState() {
        this.isDrawingRectangle = false;
        this.rectangleStartPoint = null;
        this.rectangleEndPoint = null;
    }

    _updateRectangleCornersDuringDrag(cornerIndex, x, y){
        if (this.points.length !== 4) return;

        const clampedPosition = this._ensureMinimumSizeWhenDraggingCorner(cornerIndex, x, y);
        const updatedPosition = { x: clampedPosition.x, y: clampedPosition.y };

        this._updateCornerAndAdjacentPoints(cornerIndex, updatedPosition);
    }

    _updateCornerAndAdjacentPoints(cornerIndex, position) {
        const TOP_LEFT = 0;
        const TOP_RIGHT = 1;
        const BOTTOM_RIGHT = 2;
        const BOTTOM_LEFT = 3;

        switch(cornerIndex){
            case TOP_LEFT:
                this.points[0] = position;
                this.points[1] = { x: this.points[1].x, y: position.y };
                this.points[3] = { x: position.x, y: this.points[3].y };
                this.points[2] = { x: this.points[1].x, y: this.points[3].y };
                break;
            case TOP_RIGHT:
                this.points[1] = position;
                this.points[0] = { x: this.points[0].x, y: position.y };
                this.points[2] = { x: position.x, y: this.points[2].y };
                this.points[3] = { x: this.points[0].x, y: this.points[2].y };
                break;
            case BOTTOM_RIGHT:
                this.points[2] = position;
                this.points[1] = { x: position.x, y: this.points[1].y };
                this.points[3] = { x: this.points[3].x, y: position.y };
                this.points[0] = { x: this.points[3].x, y: this.points[1].y };
                break;
            case BOTTOM_LEFT:
                this.points[3] = position;
                this.points[0] = { x: position.x, y: this.points[0].y };
                this.points[2] = { x: this.points[2].x, y: position.y };
                this.points[1] = { x: this.points[2].x, y: this.points[0].y };
                break;
        }
    }

    _ensureMinimumRectangleSize(startPoint, currentPoint) {
        let x = currentPoint.x;
        let y = currentPoint.y;
        const deltaX = x - startPoint.x;
        const deltaY = y - startPoint.y;

        if (Math.abs(deltaX) < this.minimumRectangleSize) {
            x = startPoint.x + (deltaX >= 0 ? this.minimumRectangleSize : -this.minimumRectangleSize);
        }
        if (Math.abs(deltaY) < this.minimumRectangleSize) {
            y = startPoint.y + (deltaY >= 0 ? this.minimumRectangleSize : -this.minimumRectangleSize);
        }

        return { x, y };
    }

    _ensureMinimumSizeWhenDraggingCorner(cornerIndex, x, y) {
        if (this.points.length !== 4) return { x, y };

        const oppositeCornerIndex = (cornerIndex + 2) % 4;
        const oppositeCorner = this.points[oppositeCornerIndex];
        const deltaX = x - oppositeCorner.x;
        const deltaY = y - oppositeCorner.y;

        let clampedX = x;
        let clampedY = y;

        if (Math.abs(deltaX) < this.minimumRectangleSize) {
            clampedX = oppositeCorner.x + (deltaX >= 0 ? this.minimumRectangleSize : -this.minimumRectangleSize);
        }
        if (Math.abs(deltaY) < this.minimumRectangleSize) {
            clampedY = oppositeCorner.y + (deltaY >= 0 ? this.minimumRectangleSize : -this.minimumRectangleSize);
        }

        return { x: clampedX, y: clampedY };
    }

    _scalePointsByFactor(scaleX, scaleY){
        if(!this.points || this.points.length === 0) return;

        for (let i = 0; i < this.points.length; i++){
            this.points[i].x *= scaleX;
            this.points[i].y *= scaleY;
        }
    }

    _findClosestPointIndex(x, y) {
        let closestPoint = { index: -1, distanceSquared: Infinity };

        for (let i = 0; i < this.points.length; i++){
            const deltaX = this.points[i].x - x;
            const deltaY = this.points[i].y - y;
            const distanceSquared = deltaX * deltaX + deltaY * deltaY;

            if (distanceSquared < closestPoint.distanceSquared) {
                closestPoint = { index: i, distanceSquared };
            }
        }

        return closestPoint.index;
    }
}
