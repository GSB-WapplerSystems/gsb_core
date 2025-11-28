/**
 * @typedef {Object} HotSpotCanvasParams
 * @property {HTMLCanvasElement} canvas
 * @property {string} imageUrl
 */

export class HotSpotCanvas {

    /**
     * @param {HotSpotCanvasParams} params
     */
    constructor({canvas, imageUrl }){
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.bgImage = null;
        this._initialized = false;
        this.lastCanvasSize = null;

        this._loadBackgroundImageFromUrl(imageUrl);
        this._bindWindowResizeEvent();
        this._handleResize();
        this._initializeResponsiveSizing();
    }

    _initializeResponsiveSizing() {
        const container = this.canvas.parentElement || document.documentElement;
        const resizeCallback = () => this._handleResize();

        this._canvasResizeObserver = new ResizeObserver(resizeCallback);
        this._canvasResizeObserver.observe(this.canvas);

        this._containerResizeObserver = new ResizeObserver(resizeCallback);
        this._containerResizeObserver.observe(container);

        this._waitForCanvasLayout();
    }

    _waitForCanvasLayout() {
        const checkLayout = () => {
            const canvasRect = this.canvas.getBoundingClientRect();
            if (canvasRect.width === 0 || canvasRect.height === 0) {
                requestAnimationFrame(checkLayout);
                return;
            }
            this._handleResize();
        };
        requestAnimationFrame(checkLayout);
    }

    _waitForReadyAndApplyInit(initializationCallback) {
        const isReady = () => {
            const canvasRect = this.canvas.getBoundingClientRect();
            const isCanvasReady = canvasRect.width > 0 && canvasRect.height > 0;
            const isBackgroundReady = !!this.bgImage;
            return isCanvasReady && isBackgroundReady;
        };

        const checkReady = () => {
            if (!isReady()) {
                requestAnimationFrame(checkReady);
                return;
            }
            initializationCallback?.();
            this._initialized = true;
        };
        requestAnimationFrame(checkReady);
    }

    _loadBackgroundImageFromUrl(imageUrl){
        const image = new Image();
        image.onload = () => {
            this.bgImage = image;
            this._applyCanvasStyles();
            this._layoutCanvasToContainer();
            this._handleResize();
        };
        image.src = imageUrl;
    }

    _applyCanvasStyles() {
        this.canvas.style.display = 'block';
        this.canvas.style.margin = '0 auto';
        this.canvas.style.maxWidth = '';
        this.canvas.style.maxHeight = '';
        this.canvas.style.aspectRatio = `${this.bgImage.naturalWidth} / ${this.bgImage.naturalHeight}`;
    }

    _bindWindowResizeEvent(){
        window.addEventListener('resize', () => this._handleResize());
    }

    _handleResize(rescalePointsCallback){
        this._layoutCanvasToContainer();

        const canvasRect = this.canvas.getBoundingClientRect();
        const cssWidth = Math.floor(canvasRect.width);
        const cssHeight = Math.floor(canvasRect.height);

        if (!cssWidth || !cssHeight) return;

        if (this.lastCanvasSize){
            this._rescalePointsIfNeeded(cssWidth, cssHeight, rescalePointsCallback);
        }

        this._updateCanvasDimensions(cssWidth, cssHeight);
        this.lastCanvasSize = { w: cssWidth, h: cssHeight };
        this._draw();
    }

    _rescalePointsIfNeeded(cssWidth, cssHeight, rescalePointsCallback) {
        const scaleX = cssWidth / this.lastCanvasSize.w;
        const scaleY = cssHeight / this.lastCanvasSize.h;

        if (isFinite(scaleX) && isFinite(scaleY) && scaleX > 0 && scaleY > 0) {
            rescalePointsCallback?.(scaleX, scaleY);
        }
    }

    _updateCanvasDimensions(cssWidth, cssHeight) {
        const devicePixelRatio = Math.max(1, window.devicePixelRatio || 1);
        const targetWidth = Math.round(cssWidth * devicePixelRatio);
        const targetHeight = Math.round(cssHeight * devicePixelRatio);

        if (this.canvas.width !== targetWidth || this.canvas.height !== targetHeight) {
            this.canvas.width = targetWidth;
            this.canvas.height = targetHeight;
            this.ctx.setTransform(devicePixelRatio, 0, 0, devicePixelRatio, 0, 0);
        }
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
        this.ctx.drawImage(
            this.bgImage,
            0,
            0,
            this.canvas.width,
            this.canvas.height
        );
        this.ctx.restore();
    }

    _layoutCanvasToContainer() {
        if (!this.bgImage) return;

        const imageAspectRatio = this._calculateImageAspectRatio();
        const container = this.canvas.parentElement || document.documentElement;
        const viewportDimensions = this._getViewportDimensions();
        const containerDimensions = this._getContainerDimensions(container);

        const targetWidth = this._calculateTargetWidth(
            containerDimensions,
            viewportDimensions,
            imageAspectRatio
        );

        if (!isFinite(targetWidth) || targetWidth <= 0) return;

        const targetHeight = Math.floor(targetWidth / imageAspectRatio);
        this._applyCanvasDimensions(targetWidth, targetHeight);
    }

    _calculateImageAspectRatio() {
        return this.bgImage.naturalWidth / this.bgImage.naturalHeight;
    }

    _getViewportDimensions() {
        return {
            width: window.visualViewport?.width || document.documentElement.clientWidth || window.innerWidth,
            height: window.visualViewport?.height || document.documentElement.clientHeight || window.innerHeight
        };
    }

    _getContainerDimensions(container) {
        const containerRect = container.getBoundingClientRect();
        return {
            clientWidth: container.clientWidth || containerRect.width || 0
        };
    }

    _calculateTargetWidth(containerDimensions, viewportDimensions, imageAspectRatio) {
        const availableWidth = Math.min(containerDimensions.clientWidth, viewportDimensions.width);
        const maxHeight = Math.max(0, Math.floor(viewportDimensions.height * 0.9));
        const maxWidthFromViewportHeight = Math.floor(maxHeight * imageAspectRatio);
        const maxWidthFromViewport = Math.floor(viewportDimensions.width * 0.99);

        return Math.min(availableWidth, maxWidthFromViewportHeight, maxWidthFromViewport);
    }

    _applyCanvasDimensions(targetWidth, targetHeight) {
        this.canvas.style.width = `${Math.round(targetWidth)}px`;
        this.canvas.style.height = `${Math.round(targetHeight)}px`;
    }

    _canvasPos(event){
        const canvasRect = this.canvas.getBoundingClientRect();
        return {
            x: (event.clientX - canvasRect.left),
            y: (event.clientY - canvasRect.top)
        };
    }

    _clear(){
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }
}
