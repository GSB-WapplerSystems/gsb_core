import { HotSpotCanvas } from './hotSpotCanvas.js';
import { Geometry } from './geometry.js';
//#region Type Definitions
/**
 * @typedef {Object} HotSpotSelectorOptions
 * @property {'rect' | 'polygon'} [mode]
 * @property {string} [fillColor]
 * @property {string} [strokeColor]
 * @property {string} [pointColor]
 * @property {string} [previewColor]
 * @property {boolean} [enableGrid]
 * @property {number} [minRectSize]
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
//#endregion

export class HotSpotSelector extends HotSpotCanvas {

    //#region Constructor
    /**
     * @param {HotSpotSelectorParams} params
     */
    constructor({canvas, imageUrl, ui, onShapeChange, opts = {}, initPoints }){
        super({canvas, imageUrl});

        this.ui = ui || {};
        this.onShapeChange = typeof onShapeChange === 'function' ? onShapeChange : () => {};

      // State
        this.points = [];
        this.mode = opts.mode === 'rect' ? 'rect' : 'polygon'; // configurable initial mode
        this.isConnected = false; // closed polygon state
        this.isDrawingRect = false;
        this.rectStart = null;
        this.rectEnd = null;
        this.dragIndex = -1;
        this.hoveringIndex = -1;
        this.isPanning = false;
        this.panLast = null;
        this.lastSize = null; // {w,h}

        // Config
        this.minRectSize = (opts && Number.isFinite(opts.minRectSize)) ? opts.minRectSize : 10;
        this.enableGrid = Boolean(opts.enableGrid);
        this.gridStep = 40;
        this.pointRadius = 4;
        this.closeThreshold = 10; // px
        this.fillColor = (opts && opts.fillColor) || 'rgba(30, 58, 138, 0.25)';
        this.strokeColor = (opts && opts.strokeColor) || '#fff';
        this.pointColor = (opts && opts.pointColor) || '#000';
        this.previewColor = (opts && opts.previewColor) || '#38bdf8';

        // Init
        this._bindUI();
        this._bindCanvasEvents();
        this.canvas.style.cursor = 'crosshair';

        this._pendingInit = initPoints || null;
        this._waitForReadyAndApplyInit(this._applyInitialPoints.bind(this));
    }
    //#endregion

    //#region Public API
    setMode(mode){
        const m = mode === 'rect' ? 'rect' : 'polygon';
        if (this.mode === m) return;
        this.mode = m;
        this.isConnected = false;
        this.isDrawingRect = false;
        this.rectStart = null;
        this.rectEnd = null;
        this.points = [];
        this._draw();
    }
    //#endregion

    //#region Initialization & Setup
    _applyInitialPoints() {
        if (this._pendingInit && this._pendingInit.normalized && this._pendingInit.basis && this._pendingInit.basis.drawRect) {
            const { normalized } = this._pendingInit;
            const rNow = this._getCurrentDrawRect();
            if (!rNow) return; // can't map without background rect
            const { dx, dy, dw, dh } = rNow;
            this.points = normalized.map(({u, v}) => ({ x: dx + u * dw, y: dy + v * dh }));
            this.isConnected = this.points.length >= 3;
            this._draw();
            return;
        }
    }
    //#endregion

    //#region Utilities & Helpers
    _nearestPoint(x, y, thresh = this.closeThreshold){
        return Geometry.nearestPoint(x, y, this.points, thresh);
    }

    _pointInPolygon(x, y){
        return Geometry.pointInPolygon(x, y, this.points);
    }

    _polygonCentroid(){
        return Geometry.getPolygonCentroid(this.points);
    }

    _bindUI(){
        const { clearBtn } = this.ui;
        if (clearBtn) clearBtn.addEventListener('click', () => this._clearAll());
    }

    _clearAll(){
        this.points = [];
        this.isConnected = false;
        this.isDrawingRect = false;
        this.rectStart = null;
        this.rectEnd = null;
        this._draw();
    }

    _bindCanvasEvents(){
        this.canvas.addEventListener('click', (e) => this._onCanvasClick(e));
        this.canvas.addEventListener('contextmenu', (e) => this._onContextMenu(e));
        this.canvas.addEventListener('mousemove', (e) => this._onMouseMove(e));
        this.canvas.addEventListener('mousedown', (e) => this._onMouseDown(e));
        this.canvas.addEventListener('mouseup', () => this._onMouseUp());
        this.canvas.addEventListener('mouseleave', () => this._onMouseLeave());
    }

    _computeDrawRect(imgW, imgH, canvasW, canvasH, mode){
        if (mode === 'stretch') return { dx:0, dy:0, dw:canvasW, dh:canvasH };
        const scaleContain = Math.min(canvasW / imgW, canvasH / imgH);
        const scaleCover = Math.max(canvasW / imgW, canvasH / imgH);
        const s = mode === 'cover' ? scaleCover : scaleContain;
        const dw = imgW * s, dh = imgH * s;
        const dx = (canvasW - dw) / 2;
        const dy = (canvasH - dh) / 2;
        return { dx, dy, dw, dh };
    }
    //#endregion

    //#region Background & Layout


    _handleResize(){
        super._handleResize(this._rescalePoints.bind(this));
    }

    _getCurrentDrawRect(){
        // Useful if you emit normalized coords
        return { dx: 0, dy: 0, dw: this.canvas.width, dh: this.canvas.height };
    }
    //#endregion

    //#region Shape Data & Events
    _getShapeData() {
        // Canvas-space points
        const ptsCanvas = this.points.map(p => ({
          x: Math.round(p.x * 1000) / 1000,
          y: Math.round(p.y * 1000) / 1000
        }));

        // Center in canvas space
        const c = this._polygonCentroid() || { x: null, y: null };
        const centerCanvas = {
          x: c && c.x != null ? Math.round(c.x * 1000) / 1000 : null,
          y: c && c.y != null ? Math.round(c.y * 1000) / 1000 : null
        };

        // If background known, also produce normalized-to-drawRect points (u,v in [0..1])
        const drawRect = this._getCurrentDrawRect();
        let ptsNormalized = null;
        let centerNormalized = null;

        if (drawRect) {
            const { dx, dy, dw, dh } = drawRect;
            const toUV = (p) => ({ u: (p.x - dx) / dw, v: (p.y - dy) / dh });
            ptsNormalized = ptsCanvas.map(toUV);
            centerNormalized = centerCanvas.x == null ? null : toUV(centerCanvas);
        }

        return {
          // Original (canvas-space) for backward compatibility
            points: ptsCanvas,
            center: centerCanvas,

          // Recommended robust payload:
            normalized: ptsNormalized,     // [{u,v}] relative to current drawRect
            normalizedCenter: centerNormalized,
            basis: drawRect ? {
                drawRect,                                // {dx,dy,dw,dh}
                fit: 'contain',                         // 'contain' | 'cover' | 'stretch'
                image: { w: this.bgImage.naturalWidth, h: this.bgImage.naturalHeight },
                canvas: { w: this.canvas.width, h: this.canvas.height }
            } : null
        };
    }

    _emitShape() {
        if (!this.isConnected) return;
        try {
            this.onShapeChange(this._getShapeData());
        } catch (error) {
            console.error('onShapeChange error', error);
        }
    }
    //#endregion

    //#region Rendering
    _draw(){
        this._clear();
        this._drawBackground();
        this._drawGrid();
        if (this.mode === 'rect' && this.isDrawingRect && this.rectStart && this.rectEnd) this._drawRectPreview(this.rectStart, this.rectEnd);
        if (this.isConnected) { this._drawPath(true); this._drawPoints(); this._drawCentroid(); }
        else { this._drawPath(false); this._drawPoints(); this._drawFirstPointHint(); }
    }

    _drawGrid(){
        if (!this.enableGrid) return;
        const { ctx } = this;
        const step = this.gridStep;
        ctx.save();
        ctx.strokeStyle = '#ffffff0f';
        ctx.lineWidth = 1;
        for (let x = 0; x < this.canvas.width; x += step) { ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, this.canvas.height); ctx.stroke(); }
        for (let y = 0; y < this.canvas.height; y += step) { ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(this.canvas.width, y); ctx.stroke(); }
        ctx.restore();
    }

    _drawPoints(){
        if(!this.points) return;
        const { ctx } = this;
        ctx.save();
        ctx.fillStyle = this.pointColor;
        ctx.strokeStyle = this.strokeColor;
        ctx.textBaseline = 'top';
        this.points.forEach((p) => {
            ctx.beginPath();
            ctx.arc(p.x, p.y, this.pointRadius, 0, Math.PI * 2);
            ctx.fill();
            ctx.stroke();
            ctx.fillStyle = this.pointColor;
        });
        ctx.restore();
    }

    _drawPath(close = true){
        if(!this.points) return;
        if (this.points.length < 2) return;
        const { ctx } = this;
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(this.points[0].x, this.points[0].y);
        for (let i = 1; i < this.points.length; i++) ctx.lineTo(this.points[i].x, this.points[i].y);
        if (close) ctx.closePath();

        // NEW: fill only when the shape is connected (closed)
        if (close && this.isConnected) {
            ctx.fillStyle = this.fillColor;
            ctx.fill(); // fill under the stroke
        }

        ctx.lineWidth = 2;
        ctx.strokeStyle = this.strokeColor;
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.stroke();
        ctx.restore();
    }

    _drawCentroid(){
        if (!this.isConnected || this.points.length < 2) return;
        const c = this._polygonCentroid();
        if (!c) return;
        const { ctx } = this;
        ctx.save();
        ctx.fillStyle = this.strokeColor;
        const r = 5;
        ctx.beginPath();
        ctx.arc(c.x, c.y, r, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }

    _drawFirstPointHint(){
        if (this.isConnected) return;
        if (this.mode !== 'polygon') return;
        if (this.points.length < 3) return;
        const p0 = this.points[0];
        const { ctx } = this;
        ctx.save();
        ctx.beginPath();
        ctx.lineWidth = 2;
        ctx.setLineDash([4,4]);
        ctx.strokeStyle = this.strokeColor;
        ctx.arc(p0.x, p0.y, this.pointRadius + 8, 0, Math.PI * 2);
        ctx.stroke();
        ctx.restore();
    }

    _drawRectPreview(a, b){
        const x0 = Math.min(a.x, b.x), y0 = Math.min(a.y, b.y);
        const x1 = Math.max(a.x, b.x), y1 = Math.max(a.y, b.y);
        const { ctx } = this;
        ctx.save();
        ctx.setLineDash([6,6]);
        ctx.lineWidth = 2;
        ctx.strokeStyle = this.previewColor;
        ctx.strokeRect(x0, y0, x1 - x0, y1 - y0);
        ctx.restore();
    }
    //#endregion

    //#region Rectangle Helpers
    _finalizeRectangle(a, b){

        const end = this._clampPreviewToMinSize(a, b);

        const x0 = Math.min(a.x, end.x), y0 = Math.min(a.y, end.y);
        const x1 = Math.max(a.x, end.x), y1 = Math.max(a.y, end.y);

        this.points.length = 0;
        // TL, TR, BR, BL
        this.points.push({x:x0,y:y0},{x:x1,y:y0},{x:x1,y:y1},{x:x0,y:y1});
        this.isConnected = true;
        this.isDrawingRect = false;
        this.rectStart = this.rectEnd = null;
        this._draw();
        this._emitShape();
    }

    _enforceRectDrag(i, x, y){
        if (this.points.length !== 4) return;

        const clamped = this._clampCornerDragToMin(i, x, y);
        x = clamped.x;
        y = clamped.y;

        switch(i){
          case 0: // TL
            this.points[0] = {x, y};
            this.points[1] = {x: this.points[1].x, y};
            this.points[3] = {x, y: this.points[3].y};
            this.points[2] = {x: this.points[1].x, y: this.points[3].y};
            break;
          case 1: // TR
            this.points[1] = {x, y};
            this.points[0] = {x: this.points[0].x, y};
            this.points[2] = {x, y: this.points[2].y};
            this.points[3] = {x: this.points[0].x, y: this.points[2].y};
            break;
          case 2: // BR
            this.points[2] = {x, y};
            this.points[1] = {x, y: this.points[1].y};
            this.points[3] = {x: this.points[3].x, y};
            this.points[0] = {x: this.points[3].x, y: this.points[1].y};
            break;
          case 3: // BL
            this.points[3] = {x, y};
            this.points[0] = {x, y: this.points[0].y};
            this.points[2] = {x: this.points[2].x, y};
            this.points[1] = {x: this.points[2].x, y: this.points[0].y};
            break;
        }
    }

    _clampPreviewToMinSize(start, curr) {
        const min = this.minRectSize;
        let x = curr.x;
        let y = curr.y;

        const dx = x - start.x;
        const dy = y - start.y;

        if (Math.abs(dx) < min) x = start.x + (dx >= 0 ? min : -min);
        if (Math.abs(dy) < min) y = start.y + (dy >= 0 ? min : -min);

        return { x, y };
    }

    _clampCornerDragToMin(i, x, y) {
        const min = this.minRectSize;
        if (this.points.length !== 4) return { x, y };

        // Opposite corner index across the diagonal
        const oppIndex = (i + 2) % 4;
        const opp = this.points[oppIndex];

        const dx = x - opp.x;
        const dy = y - opp.y;

        if (Math.abs(dx) < min) x = opp.x + (dx >= 0 ? min : -min);
        if (Math.abs(dy) < min) y = opp.y + (dy >= 0 ? min : -min);

        return { x, y };
    }
    //#endregion

    //#region Helper Methods
    _rescalePoints(sx, sy){
        if(!this.points) return;
        for (let i = 0; i < this.points.length; i++){
            this.points[i].x *= sx;
            this.points[i].y *= sy;
        }
    }
    //#endregion

    //#region Event Handlers
    _onCanvasClick(e){
        if (this.isConnected) return;
        if (this.mode === 'rect') return;
        const {x, y} = this._canvasPos(e);

      // close loop if clicking first point
        if (this.points.length >= 3){
            const idx = this._nearestPoint(x, y);
            if (idx === 0){ this.isConnected = true; this._draw(); this._emitShape(); return; }
        }

        this.points.push({x, y});
        this._draw();
    }

    _onContextMenu(e){
        e.preventDefault();
        if (this.mode === 'rect' && this.isConnected) return; // keep rectangle intact
        if (!this.points.length) return;
        const {x, y} = this._canvasPos(e);
        let best = {i: -1, d2: Infinity};
        for (let i = 0; i < this.points.length; i++){
            const dx = this.points[i].x - x, dy = this.points[i].y - y;
            const d2 = dx*dx + dy*dy;
            if (d2 < best.d2) best = {i, d2};
        }
        if (best.i !== -1) this.points.splice(best.i, 1);
        if (this.points.length < 2) this.isConnected = false;
        this._draw();
    }

    _onMouseMove(e){
        const {x, y} = this._canvasPos(e);

      // Rectangle preview while dragging to create
        if (!this.isConnected && this.mode === 'rect'){
            if (this.isDrawingRect && this.rectStart){
                this.rectEnd = this._clampPreviewToMinSize(this.rectStart, { x, y });
                this._draw();
            }
            this.canvas.style.cursor = 'crosshair';
            return;
        }

      // Polygon mode not connected: indicate close-on-first
        if (!this.isConnected && this.mode === 'polygon'){
            if (this.points.length >= 3){
            const idx = this._nearestPoint(x, y);
            if (idx === 0){ this.canvas.style.cursor = 'pointer'; return; }
            }
            this.canvas.style.cursor = 'crosshair';
            return;
        }

        if (!this.isConnected){ this.canvas.style.cursor = 'crosshair'; return; }

      // Whole-shape pan
        if (this.isPanning && this.panLast){
            const dx = x - this.panLast.x;
            const dy = y - this.panLast.y;
            for (let i = 0; i < this.points.length; i++){
                this.points[i].x += dx;
                this.points[i].y += dy;
            }
            this.panLast = {x, y};
            this.canvas.style.cursor = 'grabbing';
            this._draw();
            return;
        }

      // Vertex drag
        if (this.dragIndex !== -1){
            if (this.mode === 'rect' && this.isConnected) this._enforceRectDrag(this.dragIndex, x, y);
            else { this.points[this.dragIndex].x = x; this.points[this.dragIndex].y = y; }
            this._draw();
            return;
        }

      // Hover logic
        this.hoveringIndex = this._nearestPoint(x, y);
        if (this.hoveringIndex !== -1){ this.canvas.style.cursor = 'pointer'; return; }
        this.canvas.style.cursor = this._pointInPolygon(x, y) ? 'grab' : 'default';
    }

    _onMouseDown(e){
        const {x, y} = this._canvasPos(e);

        // Begin rectangle drawing
        if (!this.isConnected && this.mode === 'rect'){
            this.isDrawingRect = true; this.rectStart = {x, y}; this.rectEnd = {x, y}; e.preventDefault(); this._draw(); return;
        }

        if (!this.isConnected) return;

        // Start vertex drag
        const idx = this._nearestPoint(x, y);
        if (idx !== -1){ this.dragIndex = idx; this.canvas.style.cursor = 'grabbing'; e.preventDefault(); return; }

        // Start whole-shape pan
        if (this._pointInPolygon(x, y)){
            this.isPanning = true; this.panLast = {x, y}; this.canvas.style.cursor = 'grabbing'; e.preventDefault(); return;
        }
    }

    _onMouseUp(){
        if (!this.isConnected && this.mode === 'rect' && this.isDrawingRect && this.rectStart && this.rectEnd){ this._finalizeRectangle(this.rectStart, this.rectEnd); return; }
        const wasDragging = this.dragIndex !== -1;
        const wasPanning = this.isPanning;
        if (this.dragIndex !== -1){ this.dragIndex = -1; this._draw(); }
        if (this.isPanning){ this.isPanning = false; this.panLast = null; }
        if (this.isConnected && (wasDragging || wasPanning)) this._emitShape();
    }

    _onMouseLeave(){
        if (this.dragIndex !== -1) this.dragIndex = -1;
        if (this.isPanning){ this.isPanning = false; this.panLast = null; }
        this.hoveringIndex = -1;
        this.canvas.style.cursor = this.isConnected ? 'default' : 'crosshair';
    }
    //#endregion
}
