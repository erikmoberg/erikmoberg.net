export class IconPreview extends HTMLElement {
    previewSize = 128;
    minPngSize = 1;
    maxPngSize = 2000;
    pngSize = 128;
    selectedIcon = null;
    currentState = null;
    downloadIcon = '<svg viewBox="0 0 32 32" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" stroke="none" d="M28 18.667c-0.8 0-1.333 0.533-1.333 1.333v5.333c0 0.8-0.533 1.333-1.333 1.333h-18.667c-0.8 0-1.333-0.533-1.333-1.333v-5.333c0-0.8-0.533-1.333-1.333-1.333s-1.333 0.533-1.333 1.333v5.333c0 2.267 1.733 4 4 4h18.667c2.267 0 4-1.733 4-4v-5.333c0-0.8-0.533-1.333-1.333-1.333z M15.067 20.933c0.133 0.133 0.267 0.267 0.4 0.267 0.133 0.133 0.4 0.133 0.533 0.133s0.4 0 0.533-0.133c0.133-0.133 0.267-0.133 0.4-0.267l6.667-6.667c0.533-0.533 0.533-1.333 0-1.867s-1.333-0.533-1.867 0l-4.4 4.4v-12.8c0-0.8-0.533-1.333-1.333-1.333s-1.333 0.533-1.333 1.333v12.8l-4.4-4.4c-0.533-0.533-1.333-0.533-1.867 0s-0.533 1.333 0 1.867l6.667 6.667z"></path></svg>';

    styles = `
:host {
    display: flex;
    flex-direction: column;
    width: 100%;
    box-sizing: border-box;
    gap: 12px;
    color: var(--preview-text);
    --preview-text: #333;
    --preview-muted: #555;
    --preview-border: #aaa;
    --preview-control: #fff;
    --preview-surface: #f4f4f4;
    --preview-checker: #e5e5e5;
    color-scheme: light;
}
:host-context(body.dark-theme) {
    color-scheme: dark;
    --preview-text: #d8d8d8;
    --preview-muted: #aaa;
    --preview-border: #555;
    --preview-control: #333;
    --preview-surface: #303030;
    --preview-checker: #444;
}
:host h3 {
    margin: 0;
    font-size: 1.05rem;
}
button {
    min-height: 40px;
    padding: 8px 10px;
    border: 1px solid #da4526;
    border-radius: 5px;
    background-color: #da4526;
    font: inherit;
    cursor: pointer;
    color: #fff;
}
button:hover {
    background-color: #9e321c;
    border-color: #9e321c;
}
button:focus-visible, input:focus-visible {
    outline: 3px solid #da452680;
    outline-offset: 2px;
}
input[type=number] {
    box-sizing: border-box;
    width: 110px;
    min-height: 40px;
    padding: 8px 10px;
    border: 1px solid var(--preview-border);
    border-radius: 5px;
    background-color: var(--preview-control);
    color: var(--preview-text);
    font: inherit;
}
.preview-container {
    display: grid;
    min-height: 190px;
    place-items: center;
    padding: 16px;
    border-radius: 6px;
    background-color: var(--preview-surface);
    background-image: linear-gradient(45deg, var(--preview-checker) 25%, transparent 25%),
        linear-gradient(-45deg, var(--preview-checker) 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, var(--preview-checker) 75%),
        linear-gradient(-45deg, transparent 75%, var(--preview-checker) 75%);
    background-position: 0 0, 0 8px, 8px -8px, -8px 0;
    background-size: 16px 16px;
}
.preview-container>svg {
    display: block;
    width: min(180px, 100%);
    height: auto;
    aspect-ratio: 1;
}
.icon-details {
    color: var(--preview-muted);
    font-size: 0.9rem;
}
.download-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.save-design-button {
    width: 100%;
}
.download-actions button svg {
    vertical-align: -0.12em;
}
.png-size {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
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

        this.currentState = state;
        this.selectedIcon = state.selectedIcon;
        const scale = state.zoom / 100;
        const rotation = state.rotationAngle || 0; // Get the rotation angle from state
        // Note Firefox fix: path.style.transformOrigin = "center";

        const backgroundMarkup = this.getBackgroundMarkup(state.backgroundShape);
        const iconMarkup = state.includeIcon ? `
        <path fill="url('#myGradient')" stroke="none" d="${state.selectedIcon.path}"
            transform="${state.text ? "translate(0 -8) " : ""}scale(${state.text ? scale * 0.5 : scale}) rotate(${rotation})"
            transform-origin="center" style="transform-origin: center center;"></path>` : "";
        const textLayout = state.text ? this.getTextLayout(state) : null;
        const textMarkup = state.text ? `
        <text id="design-text" fill="url('#myGradient')" font-family="${this.escapeAttribute(state.fontFamily)}"
            font-size="${textLayout.fontSize}" font-weight="${state.bold ? "bold" : "normal"}"
            font-style="${state.italic ? "italic" : "normal"}" text-anchor="middle"
            aria-label="${this.escapeAttribute(state.text)}">${textLayout.lines.map((line, index) =>
                `<tspan x="16" y="${textLayout.baselines[index]}">${this.escapeText(line)}</tspan>`).join("")}</text>` : "";
        const details = [
            state.includeIcon ? `${state.selectedIcon.title} by ${state.selectedIcon.set}` : "",
            state.text
        ].filter(Boolean).join(" · ");
        let markup = `
<style>
${this.styles}
</style>
<h3>Preview</h3>
<div class="preview-container">
    <svg viewBox="0 0 32 32" width="32" height="32" xmlns="http://www.w3.org/2000/svg">
        ${backgroundMarkup}
        ${iconMarkup}
        ${textMarkup}
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
 </div>
<div class="icon-details">${this.escapeText(details)}</div>
<h3>Download</h3>
<div class="download-actions">
  <button id="download-svg">${this.downloadIcon} SVG</button>
  <button id="download-png">${this.downloadIcon} PNG</button>
</div>
<button class="save-design-button" id="save-design" type="button">Save design</button>

<div class="png-size">
  <label for="png-size">PNG size</label>
  <input type="number" min="${this.minPngSize}" max="${this.maxPngSize}" step="1" value="${this.pngSize}" id="png-size" />
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

        this.shadowRoot.getElementById("save-design").addEventListener("click", () => {
            this.dispatchEvent(new CustomEvent("save-design", {
                bubbles: true,
                composed: true,
                detail: {
                    state: this.currentState,
                    svg: this.shadowRoot.querySelector(".preview-container > svg").outerHTML
                }
            }));
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

    escapeText(value) {
        return String(value).replace(/[&<>"]/g, character => ({
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            "\"": "&quot;"
        })[character]);
    }

    escapeAttribute(value) {
        return this.escapeText(value).replace(/'/g, "&#39;");
    }

    getTextLayout(state) {
        const top = state.includeIcon ? 14 : 3;
        const bottom = state.includeIcon ? 30 : 29;
        const availableHeight = bottom - top;
        const context = this.ownerDocument.createElement("canvas").getContext("2d");
        if (!context) {
            throw new Error("Could not measure text for the icon preview.");
        }

        const getLayoutAtSize = fontSize => {
            context.font = `${state.italic ? "italic " : ""}${state.bold ? "bold " : ""}${fontSize}px ${state.fontFamily}`;
            const lines = this.wrapText(state.text, fontSize, state.fontFamily, state.bold, state.italic, context);
            const contentHeight = fontSize * (1 + (lines.length - 1) * 1.15);
            const fitsWidth = lines.every(line => context.measureText(line).width <= 28);
            return { lines, fits: fitsWidth && contentHeight <= availableHeight };
        };

        let fontSize = state.fontSize;
        let layout = getLayoutAtSize(fontSize);
        if (!layout.fits) {
            let lowerBound = 0.1;
            let upperBound = fontSize;
            for (let i = 0; i < 12; i++) {
                const candidateSize = (lowerBound + upperBound) / 2;
                const candidateLayout = getLayoutAtSize(candidateSize);
                if (candidateLayout.fits) {
                    lowerBound = candidateSize;
                    layout = candidateLayout;
                } else {
                    upperBound = candidateSize;
                }
            }
            fontSize = lowerBound;
        }

        const lines = layout.lines;
        const lineHeight = fontSize * 1.15;
        const contentHeight = fontSize + (lines.length - 1) * lineHeight;
        const baselineStart = top + (bottom - top - contentHeight) / 2 + fontSize * 0.8;
        return {
            fontSize,
            lines,
            baselines: lines.map((line, index) => baselineStart + lineHeight * index)
        };
    }

    wrapText(value, fontSize, fontFamily, bold, italic, context) {
        context.font = `${italic ? "italic " : ""}${bold ? "bold " : ""}${fontSize}px ${fontFamily}`;
        const lines = [];
        for (const paragraph of value.split(/\r\n?|\n/)) {
            let line = "";
            const words = paragraph.trim().split(/\s+/).filter(Boolean);

            for (const word of words) {
                const candidate = line ? `${line} ${word}` : word;
                if (context.measureText(candidate).width <= 28) {
                    line = candidate;
                    continue;
                }

                if (line) {
                    lines.push(line);
                    line = "";
                }

                let segment = "";
                const characters = typeof Intl.Segmenter === "function"
                    ? Array.from(new Intl.Segmenter(undefined, { granularity: "grapheme" }).segment(word), part => part.segment)
                    : Array.from(word);
                for (const character of characters) {
                    if (segment && context.measureText(segment + character).width > 28) {
                        lines.push(segment);
                        segment = character;
                    } else {
                        segment += character;
                    }
                }
                line = segment;
            }

            lines.push(line);
        }
        return lines;
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
        const name = this.currentState?.includeIcon && this.selectedIcon
            ? `${this.selectedIcon.title} by ${this.selectedIcon.set}`
            : "icon";
        const text = this.currentState?.text.trim();
        const fileName = text || name;
        return `${fileName.replace(/[<>:"/\\|?*\x00-\x1f]/g, "-")}.${extension}`;
    }
}

customElements.define('icon-preview', IconPreview);
