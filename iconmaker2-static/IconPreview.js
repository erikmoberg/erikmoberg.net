export class IconPreview extends HTMLElement {
    previewSize = 128;
    minPngSize = 1;
    maxPngSize = 2000;
    pngSize = 128;
    selectedIcon = null;
    downloadIcon = '<svg viewBox="0 0 32 32" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" stroke="none" d="M28 18.667c-0.8 0-1.333 0.533-1.333 1.333v5.333c0 0.8-0.533 1.333-1.333 1.333h-18.667c-0.8 0-1.333-0.533-1.333-1.333v-5.333c0-0.8-0.533-1.333-1.333-1.333s-1.333 0.533-1.333 1.333v5.333c0 2.267 1.733 4 4 4h18.667c2.267 0 4-1.733 4-4v-5.333c0-0.8-0.533-1.333-1.333-1.333z M15.067 20.933c0.133 0.133 0.267 0.267 0.4 0.267 0.133 0.133 0.4 0.133 0.533 0.133s0.4 0 0.533-0.133c0.133-0.133 0.267-0.133 0.4-0.267l6.667-6.667c0.533-0.533 0.533-1.333 0-1.867s-1.333-0.533-1.867 0l-4.4 4.4v-12.8c0-0.8-0.533-1.333-1.333-1.333s-1.333 0.533-1.333 1.333v12.8l-4.4-4.4c-0.533-0.533-1.333-0.533-1.867 0s-0.533 1.333 0 1.867l6.667 6.667z"></path></svg>';

    styles = `
:host {
    display: flex;
    text-align: center;
    flex-direction: column;
    width: 164px;
}
:host h3 {
    margin: 0 0 10px;
}
button {
    border: none;
    padding: 10px;
    margin-top: 5px;
    background-color: #da4526;
    font-size: inherit;
    cursor: pointer;
    color: #fff;
    border-radius: var(--border-radius);
}
button:hover {
    background-color: #9e321c;
}
input[type=number] {
    font-size: inherit;
    border: 1px solid #ccc;
    padding: 4px;
    background-color: #eee;
    width: 70px;
    border-radius: var(--border-radius);
}
.spacer {
    margin-bottom: 15px;
}
.preview-container>svg {
    width: ${this.previewSize}px;
    height: ${this.previewSize}px;
}
`;

    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
    }

    async connectedCallback() {
        this.render(null);
    }

    disconnectedCallback() {
    }

    async render(state) {
        if (!state) {
            return;
        }

        this.selectedIcon = state.selectedIcon;
        const scale = state.zoom / 100;
        // Note Firefox fix: path.style.transformOrigin = "center";

        const backgroundMarkup = this.getBackgroundMarkup(state.backgroundShape);
        let markup = `
<style>
${this.styles}
</style>
<h3>Preview</h3>
<div class="preview-container">
    <svg viewBox="0 0 32 32" width="32" height="32" xmlns="http://www.w3.org/2000/svg">
        ${backgroundMarkup}
        <path fill="url('#myGradient')" stroke="none" d="${state.selectedIcon.path}"
            transform="scale(${scale})" transform-origin="center" style="transform-origin: center center;"></path>
        <defs>
            <linearGradient id="myBackgroundGradient" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="${state.fromBackgroundColor}"></stop>
                <stop offset="100%" stop-color="${state.enableBackgroundGradient ? state.toBackgroundColor : state.fromBackgroundColor}"></stop>
            </linearGradient>
            <linearGradient id="myGradient" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="${state.fromColor}"></stop>
                <stop offset="100%" stop-color="${state.enableGradient ? state.toColor : state.fromColor}"></stop>
            </linearGradient>
        </defs>
    </svg>
<div>
<div class="spacer"></div>
<div><i>${state.selectedIcon.title}<br />by ${state.selectedIcon.set}</i></div>
<div class="spacer"></div>

<h3>Download</h3>
<div>
  <button id="download-svg">${this.downloadIcon} SVG</button>
  <button id="download-png">${this.downloadIcon} PNG</button>
</div>

<div class="spacer"></div>

<div>
  <label for="png-size">PNG size</label>
  <input type="number" min="${this.minPngSize}" max="${this.maxPngSize}" value="${this.pngSize}" id="png-size" />
</div>

<canvas id="canvas" height="${this.previewSize}" width="${this.previewSize}" style="display: none;"></canvas>
`;

        this.shadowRoot.innerHTML = markup;

        this.shadowRoot.getElementById("download-svg").addEventListener("click", (ev) => {
            this.downloadSvg();
        });

        this.shadowRoot.getElementById("download-png").addEventListener("click", (ev) => {
            this.downloadPng();
        });

        this.shadowRoot.getElementById("png-size").addEventListener("input", (ev) => {
            let val = ev.target.value;
            val = Math.min(val, this.maxPngSize);
            val = Math.max(val, this.minPngSize);
            if (val !== ev.target.value) {
                this.shadowRoot.getElementById("png-size").value = val;
            }

            this.pngSize = val;
        });
    }

    getBackgroundMarkup(backgroundShape) {
        
        const size = 32;
        switch (backgroundShape) {
            case "rounded-square-background":
                return `<rect x="0" y="0" width="${size}" height="${size}" r="${size/7}" rx="${size/7}" ry="${size/7}" fill="url('#myBackgroundGradient')"></rect>`;
            case "circle-background":
                return `<circle cx="${size/2}" cy="${size/2}" r="${size/2}" fill="url('#myBackgroundGradient')"></circle>`;
            case "square-background":
                return `<rect x="0" y="0" width="${size}" height="${size}" fill="url('#myBackgroundGradient')"></rect>`;
            case "no-background":
                break;
            default:
                break;
        }

        return "";
    }

    downloadSvg() {
        const blob = new Blob(
            [this.shadowRoot.querySelector("svg").outerHTML],
            { type: 'image/svg+xml;charset=utf-8' }
        );

        const url = URL.createObjectURL(blob);
        this.downloadFile(url, this.getDownloadFileName("svg"));
    }

    downloadPng() {
        let svgElement = this.shadowRoot.querySelector('svg');
        let width = parseInt(this.shadowRoot.getElementById("png-size").value);
        width = Math.min(width, this.maxPngSize);
        width = Math.max(width, this.minPngSize);
        const height = width;

        const blob = new Blob(
            [svgElement.outerHTML],
            { type: 'image/svg+xml;charset=utf-8' }
        );

        let URL = window.URL || window.webkitURL || window;
        let blobURL = URL.createObjectURL(blob);

        let image = new Image();
        image.onload = () => {
            let canvas = this.shadowRoot.getElementById("canvas");
            canvas.width = width;
            canvas.height = height;
            let context = canvas.getContext('2d');
            context.drawImage(image, 0, 0, width, height);

            let png = canvas.toDataURL();
            this.downloadFile(png, this.getDownloadFileName("png"));
        };

        image.src = blobURL;
    }

    downloadFile(url, filename) {
        const a = document.createElement('a');
        a.href = url;
        a.download = filename || 'download';

        const clickHandler = () => {
            setTimeout(() => {
                URL.revokeObjectURL(url);
                this.removeEventListener('click', clickHandler);
            }, 150);
        };

        a.addEventListener('click', clickHandler, false);
        a.click();
    }

    getDownloadFileName(extension) {
        return `${this.selectedIcon.title} by ${this.selectedIcon.set}.${extension}`;
    }
}

customElements.define('icon-preview', IconPreview);
