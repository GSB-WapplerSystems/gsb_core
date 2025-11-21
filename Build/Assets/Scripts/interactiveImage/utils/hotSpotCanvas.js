//#region Type Definitions
/**
 * @typedef {Object} HotSpotCanvasParams
 * @property {HTMLCanvasElement} canvas
 * @property {string} imageUrl
 */
//#endregion


export class HotSpotCanvas {

    //#region Constructor
    /**
     * @param {HotSpotCanvasParams} params
     */
    constructor({canvas, imageUrl }){
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.bgImage = null;
        this._initialized = false;

        this._loadBackgroundFromUrl(imageUrl);
        this._bindWindowEvents();
        this._handleResize();
        this._initResponsiveSizing();
    }

    //#endregion

    _initResponsiveSizing() {
        const container = this.canvas.parentElement || document.documentElement;
        const roCallback = () => this._handleResize();
        this._roCanvas = new ResizeObserver(roCallback);
        this._roCanvas.observe(this.canvas);
        this._roContainer = new ResizeObserver(roCallback);
        this._roContainer.observe(container);

        const waitUntilLaidOut = () => {
            const r = this.canvas.getBoundingClientRect();
            if (r.width === 0 || r.height === 0) {
                requestAnimationFrame(waitUntilLaidOut);
                return;
            }
            this._handleResize();
        };
        requestAnimationFrame(waitUntilLaidOut);
    }

    _waitForReadyAndApplyInit(onInit) {
        const ready = () => {
            const rect = this.canvas.getBoundingClientRect();
            const canvasReady = rect.width > 0 && rect.height > 0;
            const bgReady = !!this.bgImage;
            return canvasReady && bgReady;
        };

        const tick = () => {
            if (!ready()) { requestAnimationFrame(tick); return; }
            onInit?.();
            this._initialized = true;
        };
        requestAnimationFrame(tick);
    }

    _canvasPos(evt){
        const rect = this.canvas.getBoundingClientRect();
        return { x: (evt.clientX - rect.left), y: (evt.clientY - rect.top) };
    }

    _clear(){ this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height); }

    _layoutCanvasToContainer() {
        if (!this.bgImage) return;

        const natW = this.bgImage.naturalWidth;
        const natH = this.bgImage.naturalHeight;
        const ratio = natW / natH;
        const container = this.canvas.parentElement || document.documentElement;
        const viewportW = (window.visualViewport?.width || document.documentElement.clientWidth || window.innerWidth);
        const viewportH = (window.visualViewport?.height || document.documentElement.clientHeight || window.innerHeight);
        const crect = container.getBoundingClientRect();
        const containerClientW = container.clientWidth || crect.width || 0;
        const availableW = Math.min(containerClientW, viewportW);
        const maxH = Math.max(0, Math.floor(viewportH * 0.9));
        const maxWFromVh = Math.floor(maxH * ratio);
        const maxWFromViewport = Math.floor(viewportW * 0.99);
        const targetW = Math.min(availableW, maxWFromVh, maxWFromViewport);
        if (!isFinite(targetW) || targetW <= 0) return;
        const targetH = Math.floor(targetW / ratio);
        this.canvas.style.width = `${Math.round(targetW)}px`;
        this.canvas.style.height = `${Math.round(targetH)}px`;
    }

    _draw(){
        this._clear();
        this._drawBackground();
    }

    _drawBackground(){
        if (!this.bgImage) return;
        this.ctx.save();
        this.ctx.setTransform(1, 0, 0, 1, 0, 0);
        this.ctx.imageSmoothingEnabled = true;
        this.ctx.drawImage(this.bgImage, 0, 0, this.canvas.width, this.canvas.height);
        this.ctx.restore();
    }

    _loadBackgroundFromUrl(imageUrl){
        const img = new Image();
        img.onload = () => {
            this.bgImage = img;
            this.canvas.style.display = 'block';
            this.canvas.style.margin = '0 auto';
            this.canvas.style.maxWidth = '';
            this.canvas.style.maxHeight = '';
            this.canvas.style.aspectRatio = `${img.naturalWidth} / ${img.naturalHeight}`;

            this._layoutCanvasToContainer();
            this._handleResize();
        };
        img.src = imageUrl;
    }

    _bindWindowEvents(){
        window.addEventListener('resize', () => this._handleResize());
    }

    _handleResize(rescalePoints){
        this._layoutCanvasToContainer();

        const rect = this.canvas.getBoundingClientRect();
        const cssW = Math.floor(rect.width);
        const cssH = Math.floor(rect.height);

        if (!cssW || !cssH) return;
        if (this.lastSize){
            const sx = cssW / this.lastSize.w;
            const sy = cssH / this.lastSize.h;
            if (isFinite(sx) && isFinite(sy) && sx > 0 && sy > 0) {
                rescalePoints?.(sx, sy);
            }
        }

        const dpr = Math.max(1, window.devicePixelRatio || 1);
        const wantW = Math.round(cssW * dpr);
        const wantH = Math.round(cssH * dpr);
        if (this.canvas.width !== wantW || this.canvas.height !== wantH) {
            this.canvas.width = wantW;
            this.canvas.height = wantH;
            this.ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }

        this.lastSize = { w: cssW, h: cssH };
        this._draw();
    }
}
