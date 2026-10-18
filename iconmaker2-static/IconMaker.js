import { Icons } from './Icons.js';
import { IconPreview } from './IconPreview.js';

class IconMaker extends HTMLElement {
    // state = {
    //     filterText: null,
    //     iconSet: null,
    //     selectedIcon: null,
    //     backgroundShape: null,
    //     fromColor: "#da4526",
    //     toColor: "#da4526",
    //     fromBackgroundColor: "#333333",
    //     toBackgroundColor: "#333333",
    //     zoom: 100,
    //     enableGradient: false,
    //     enableBackgroundGradient: false,
    //     rotationAngle: 0, // New property for rotation angle
    // };

    state = {
        filterText: null,
        iconSet: null,
        selectedIcon: null,
        backgroundShape: "rounded-square-background",
        fromColor: "#00e6ff",
        toColor: "#ec00ff",
        fromBackgroundColor: "#000000",
        toBackgroundColor: "#002b5e",
        zoom: 80,
        enableGradient: true,
        enableBackgroundGradient: true,
        rotationAngle: 0, 
        includeIcon: true,
        text: "",
        fontFamily: "Arial, sans-serif",
        fontSize: 12,
        bold: false,
        italic: false,
    };

    defaultIconTitle = "triangle";
    selectedIconButton = null;
    iconPreview = null;
    observedPreview = null;
    savedDesigns = [];
    savedDesignStorageKey = "flatIconMaker.savedDesigns.v1";
    savedDesignsLoadError = null;

    icons = null;
    updatePreviewDockVisibility = () => {
        if (!this.observedPreview) {
            return;
        }

        const bounds = this.observedPreview.getBoundingClientRect();
        const isVisible = bounds.bottom > 0 && bounds.top < window.innerHeight &&
            bounds.right > 0 && bounds.left < window.innerWidth;
        const isDesktop = window.matchMedia("(min-width: 761px)").matches;
        this.shadowRoot.getElementById("preview-dock").classList.toggle("hidden", !isDesktop || isVisible);
    };

    styles = `
:host {
    display: block;
    color: var(--maker-text);
    font: inherit;
    --maker-accent: #da4526;
    --maker-border: #ccc;
    --maker-text: #333;
    --maker-muted: #666;
    --maker-panel: #fff;
    --maker-control: #fff;
    --maker-control-hover: #eee;
    --maker-icon-cell: #f5f5f5;
    --maker-selected: #fff0ec;
    --maker-separator: #e5e5e5;
    color-scheme: light;
}
:host-context(body.dark-theme) {
    --maker-border: #555;
    --maker-text: #d8d8d8;
    --maker-muted: #aaa;
    --maker-panel: #252526;
    --maker-control: #333;
    --maker-control-hover: #444;
    --maker-icon-cell: #333;
    --maker-selected: #422c27;
    --maker-separator: #444;
    color-scheme: dark;
}
.maker-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.9fr);
    align-items: start;
    gap: 16px;
}
.panel {
    min-width: 0;
    padding: 18px;
    border: 1px solid var(--maker-border);
    border-radius: 8px;
    background: var(--maker-panel);
}
.panel h3 {
    margin: 0 0 12px;
    font-size: 1.05rem;
}
.panel-heading {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
}
.panel-heading p {
    margin: 0;
    color: var(--maker-muted);
    font-size: 0.875rem;
}
.browser-filters {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(150px, 0.45fr);
    gap: 12px;
    margin: 16px 0;
}
label, legend {
    font-weight: 600;
}
input, select, textarea, button {
    font: inherit;
}
input[type="search"], input[type="text"], textarea, select, input[type="number"] {
    box-sizing: border-box;
    width: 100%;
    padding: 8px 10px;
    border: 1px solid var(--maker-border);
    border-radius: 5px;
    background-color: var(--maker-control);
    color: inherit;
}
input[type="search"], input[type="text"], select, input[type="number"] {
    height: 42px;
}
textarea {
    min-height: 64px;
    resize: vertical;
}
input[type="search"]:focus-visible, select:focus-visible,
input[type="number"]:focus-visible, textarea:focus-visible, button:focus-visible,
input[type="radio"]:focus-visible, input[type="checkbox"]:focus-visible,
input[type="range"]:focus-visible {
    outline: 3px solid #da452680;
    outline-offset: 2px;
}
#svg-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(48px, 1fr));
    gap: 6px;
    max-height: 360px;
    overflow-y: auto;
    padding: 2px;
}
#svg-container .icon-option {
    display: grid;
    place-items: center;
    min-width: 0;
    aspect-ratio: 1;
    padding: 7px;
    border: 1px solid transparent;
    border-radius: 6px;
    background: var(--maker-icon-cell);
    color: var(--maker-accent);
    cursor: pointer;
}
#svg-container .icon-option:hover {
    border-color: var(--maker-border);
    background: var(--maker-control-hover);
}
#svg-container .icon-option[aria-pressed="true"] {
    border-color: var(--maker-accent);
    background: var(--maker-selected);
    color: #9e321c;
}
#svg-container svg {
    display: block;
    width: 100%;
    height: 100%;
}
.icon-library-panel.is-disabled {
    opacity: 0.48;
}
.icon-library-panel.is-disabled #svg-container {
    filter: grayscale(1);
}
.hidden {
    display: none !important;
}
#no-hits {
    margin: 16px 0 0;
    color: var(--maker-muted);
}
.preview-dock {
    position: fixed;
    right: 16px;
    bottom: 16px;
    z-index: 20;
    box-sizing: border-box;
    width: 132px;
    padding: 8px;
    border: 1px solid var(--maker-border);
    border-radius: 8px;
    background: var(--maker-panel);
    box-shadow: 0 3px 16px #0003;
    pointer-events: none;
}
.preview-dock span {
    display: block;
    margin-bottom: 4px;
    color: var(--maker-muted);
    font-size: 0.85rem;
    font-weight: 600;
}
.preview-dock-canvas {
    display: grid;
    aspect-ratio: 1;
    place-items: center;
    padding: 6px;
    border-radius: 5px;
    background-color: var(--maker-control);
    background-image: linear-gradient(45deg, var(--maker-icon-cell) 25%, transparent 25%),
        linear-gradient(-45deg, var(--maker-icon-cell) 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, var(--maker-icon-cell) 75%),
        linear-gradient(-45deg, transparent 75%, var(--maker-icon-cell) 75%);
    background-position: 0 0, 0 8px, 8px -8px, -8px 0;
    background-size: 16px 16px;
}
.preview-dock-canvas svg {
    display: block;
    width: 100%;
    height: 100%;
}
.editor-panel {
    display: grid;
    gap: 16px;
}
.saved-designs-panel {
    grid-column: 1 / -1;
}
.saved-design-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(112px, 1fr));
    gap: 10px;
}
.saved-design-item {
    display: grid;
    gap: 6px;
    min-width: 0;
}
.saved-design-load {
    display: grid;
    place-items: center;
    width: 100%;
    aspect-ratio: 1;
    padding: 8px;
    border: 1px solid var(--maker-border);
    border-radius: 6px;
    background: var(--maker-control);
    cursor: pointer;
}
.saved-design-load:hover {
    border-color: var(--maker-accent);
    background: var(--maker-control-hover);
}
.saved-design-load img {
    width: 100%;
    height: 100%;
}
.saved-design-item p {
    overflow: hidden;
    margin: 0;
    color: var(--maker-muted);
    font-size: 0.85rem;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.saved-design-remove {
    justify-self: start;
    padding: 2px 0;
    border: 0;
    background: transparent;
    color: var(--maker-accent);
    font: inherit;
    cursor: pointer;
}
.saved-design-status {
    margin: 10px 0 0;
    color: var(--maker-muted);
}
.saved-design-status:empty {
    display: none;
}
#bottom-controls {
    display: grid;
    gap: 20px;
}
.control-group {
    display: grid;
    gap: 8px;
}
.text-style-options {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 0.65fr);
    gap: 10px;
}
.text-style-options .control-group {
    min-width: 0;
}
.text-style-options input[type="number"] {
    max-width: 100%;
}
.text-toggle-options {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.text-toggle-options label, .icon-visibility-toggle {
    font-weight: 400;
}
.icon-visibility-toggle {
    margin: 0 0 14px;
}
#bottom-controls > .control-group + .control-group {
    padding-top: 16px;
    border-top: 1px solid var(--maker-separator);
}
#background-items {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin: 0;
    padding: 0;
    border: 0;
}
#background-items legend {
    flex-basis: 100%;
    margin-bottom: 8px;
}
#background-items label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 9px;
    border: 1px solid var(--maker-border);
    border-radius: 5px;
    font-size: 0.9rem;
    font-weight: 400;
    cursor: pointer;
}
#background-items label:has(input:checked) {
    border-color: var(--maker-accent);
    background: var(--maker-selected);
}
#background-items input {
    accent-color: var(--maker-accent);
}
.color-control {
    display: flex;
    align-items: center;
    gap: 10px;
}
input[type="color"] {
    width: 44px;
    height: 38px;
    padding: 3px;
    border: 1px solid var(--maker-border);
    border-radius: 5px;
    background: var(--maker-control);
    cursor: pointer;
}
input[type="checkbox"] {
    width: 17px;
    height: 17px;
    accent-color: var(--maker-accent);
    vertical-align: middle;
}
.range-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.range-heading label {
    font-weight: 600;
}
.range-control {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    gap: 8px;
}
input[type="range"] {
    width: 100%;
    accent-color: var(--maker-accent);
}
.range-control output {
    min-width: 3.5em;
    color: var(--maker-muted);
    text-align: right;
    font-variant-numeric: tabular-nums;
}
.reset-button {
    padding: 3px 6px;
    border: 0;
    border-radius: 4px;
    background: transparent;
    color: var(--maker-accent);
    cursor: pointer;
}
.reset-button:hover {
    background: #fff0ec;
}
@media (max-width: 760px) {
    .maker-layout {
        grid-template-columns: minmax(0, 1fr);
        grid-template-areas:
            "library"
            "preview"
            "controls"
            "saved";
    }
    .icon-library-panel {
        grid-area: library;
    }
    .editor-panel {
        display: contents;
    }
    #svg-preview-container {
        grid-area: preview;
    }
    #bottom-controls {
        grid-area: controls;
    }
    .saved-designs-panel {
        grid-area: saved;
    }
    .icon-library-panel.is-disabled {
        display: none;
    }
    .browser-filters {
        grid-template-columns: minmax(0, 1fr);
    }
    #svg-container {
        max-height: 300px;
    }
}
@media (max-width: 420px) {
    .panel {
        padding: 14px;
    }
    .panel-heading {
        align-items: flex-start;
        flex-direction: column;
        gap: 2px;
    }
    .text-style-options {
        grid-template-columns: minmax(0, 1fr) minmax(0, 0.7fr);
    }
}
`;

    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
    }

    async connectedCallback() {
        window.addEventListener("scroll", this.updatePreviewDockVisibility, { passive: true });
        window.addEventListener("resize", this.updatePreviewDockVisibility);
        this.render();
    }

    disconnectedCallback() {
        window.removeEventListener("scroll", this.updatePreviewDockVisibility);
        window.removeEventListener("resize", this.updatePreviewDockVisibility);
        this.observedPreview = null;
    }

    async render() {
        this.icons = Icons.getIcons();
        let markup = "";
        markup += `
<style>
${this.styles}
</style>
<div class="maker-layout">
  <section class="panel icon-library-panel" aria-labelledby="icon-library-heading">
    <div class="panel-heading">
      <h3 id="icon-library-heading">Choose an icon</h3>
      <p>${this.icons.length} icons in 5 sets</p>
    </div>
    <div class="browser-filters">
      <div class="control-group">
        <label for="search-input">Search icons</label>
        <input type="search" placeholder="Try “home” or “arrow”" id="search-input" />
      </div>
      <div class="control-group">
        <label for="symbol-set-select">Icon set</label>
        <select id="symbol-set-select">
          <option value="">All sets</option>
          <option value="Afiado">Afiado</option>
          <option value="Entypo+">Entypo+</option>
          <option value="Feather">Feather</option>
          <option value="IcoMoon - Free">IcoMoon - Free</option>
          <option value="Simple Icons">Simple Icons</option>
        </select>
      </div>
    </div>
    <div id="svg-container" role="group" aria-label="Available icons">`;
        this.icons.forEach((icon, index) => {
            markup += `<button class="icon-option" type="button" data-index="${index}" aria-label="${icon.title} by ${icon.set}" aria-pressed="false"><svg viewBox="0 0 32 32" aria-hidden="true" focusable="false"><path fill="currentColor" stroke="none" d="${icon.path}"></path></svg></button>`;
        });
        markup += `
    </div>
    <p id="no-hits" class="hidden" role="status">No matching icons found.</p>
  </section>

  <div class="editor-panel">
    <section class="panel" id="bottom-controls" aria-label="Icon appearance">
      <div class="control-group">
        <h3>Text (optional)</h3>
        <label class="icon-visibility-toggle"><input type="checkbox" id="include-icon" checked /> Include icon (uncheck for text only)</label>
        <label for="icon-text">Add a text label</label>
        <textarea id="icon-text" maxlength="32" rows="2" placeholder="Type text; use Enter for a new line" autocomplete="off"></textarea>
        <div class="text-style-options">
          <div class="control-group">
            <label for="text-font">Font</label>
            <select id="text-font">
              <option value="Arial, sans-serif">Arial</option>
              <option value="Georgia, serif">Georgia</option>
              <option value="Verdana, sans-serif">Verdana</option>
              <option value="'Trebuchet MS', sans-serif">Trebuchet MS</option>
              <option value="'Courier New', monospace">Courier New</option>
            </select>
          </div>
          <div class="control-group">
            <label for="text-size">Size</label>
            <input type="number" id="text-size" min="6" max="72" step="1" value="${this.state.fontSize}" />
          </div>
        </div>
        <div class="text-toggle-options">
          <label><input type="checkbox" id="text-bold" /> Bold</label>
          <label><input type="checkbox" id="text-italic" /> Italic</label>
        </div>
      </div>
      <div class="control-group">
        <h3>Design color</h3>
        <div class="color-control">
          <label for="from-color">Color 1</label>
          <input type="color" id="from-color" value="${this.state.fromColor}" />
        </div>
        <label><input type="checkbox" id="enable-gradient" ${this.state.enableGradient ? "checked" : ""} /> Use a second color</label>
        <div class="color-control gradient-controls ${this.state.enableGradient ? "" : "hidden"}">
          <label for="to-color">Color 2</label>
          <input type="color" id="to-color" value="${this.state.toColor}" />
        </div>
      </div>

      <div class="control-group">
        <fieldset id="background-items">
          <legend>Background shape</legend>
          <label for="no-background"><input name="backgrounds" type="radio" id="no-background" ${this.state.backgroundShape === "no-background" ? "checked" : ""} /> None</label>
          <label for="rounded-square-background"><input name="backgrounds" type="radio" id="rounded-square-background" ${this.state.backgroundShape === "rounded-square-background" ? "checked" : ""} /> Rounded</label>
          <label for="circle-background"><input name="backgrounds" type="radio" id="circle-background" ${this.state.backgroundShape === "circle-background" ? "checked" : ""} /> Circle</label>
          <label for="square-background"><input name="backgrounds" type="radio" id="square-background" ${this.state.backgroundShape === "square-background" ? "checked" : ""} /> Square</label>
        </fieldset>
        <div class="color-control background-color-control hidden">
          <label for="from-background-color">Background color</label>
          <input type="color" id="from-background-color" value="${this.state.fromBackgroundColor}" />
        </div>
        <label class="background-color-control hidden"><input type="checkbox" id="enable-background-gradient" ${this.state.enableBackgroundGradient ? "checked" : ""} /> Use a second color</label>
        <div class="color-control background-color-control background-gradient-controls ${this.state.enableBackgroundGradient ? "" : "hidden"}">
          <label for="to-background-color">Background color 2</label>
          <input type="color" id="to-background-color" value="${this.state.toBackgroundColor}" />
        </div>
      </div>

      <div class="control-group icon-only-control">
        <div class="range-heading">
          <label for="zoom">Zoom</label>
          <button class="reset-button" id="reset-zoom" type="button" aria-label="Reset zoom">
            <svg viewBox="0 0 32 32" height="1em" width="1em" aria-hidden="true"><path fill="currentColor" d="M16 2c-4.418 0-8.418 1.791-11.313 4.687l-4.686-4.687v12h12l-4.485-4.485c2.172-2.172 5.172-3.515 8.485-3.515 6.627 0 12 5.373 12 12 0 3.584-1.572 6.801-4.063 9l2.646 3c3.322-2.932 5.417-7.221 5.417-12 0-8.837-7.163-16-16-16z"></path></svg>
          </button>
        </div>
        <div class="range-control">
          <input type="range" id="zoom" min="10" max="200" value="${this.state.zoom}" />
          <output id="zoom-label" for="zoom">${this.state.zoom}%</output>
        </div>
      </div>

      <div class="control-group icon-only-control">
        <div class="range-heading">
          <label for="rotation-angle">Rotation</label>
          <button class="reset-button" id="reset-rotation-angle" type="button" aria-label="Reset rotation">
            <svg viewBox="0 0 32 32" height="1em" width="1em" aria-hidden="true"><path fill="currentColor" d="M16 2c-4.418 0-8.418 1.791-11.313 4.687l-4.686-4.687v12h12l-4.485-4.485c2.172-2.172 5.172-3.515 8.485-3.515 6.627 0 12 5.373 12 12 0 3.584-1.572 6.801-4.063 9l2.646 3c3.322-2.932 5.417-7.221 5.417-12 0-8.837-7.163-16-16-16z"></path></svg>
          </button>
        </div>
        <div class="range-control">
          <input type="range" id="rotation-angle" min="-180" max="180" value="${this.state.rotationAngle}" />
          <output id="rotation-angle-label" for="rotation-angle">${this.state.rotationAngle}°</output>
        </div>
      </div>
    </section>
    <section class="panel" id="svg-preview-container" aria-label="Preview and download">
    </section>
  </div>
  <section class="panel saved-designs-panel" aria-labelledby="saved-designs-heading">
    <div class="panel-heading">
      <h3 id="saved-designs-heading">Saved designs</h3>
    </div>
    <div id="saved-design-list" class="saved-design-list"></div>
    <p id="saved-design-status" class="saved-design-status" role="status" aria-live="polite"></p>
  </section>
</div>
<div id="preview-dock" class="preview-dock hidden" role="img" aria-label="Floating icon preview">
  <span>Preview</span>
  <div class="preview-dock-canvas"></div>
</div>
`;

        this.shadowRoot.innerHTML = markup;

        this.updateBackgroundColorControls();
        this.iconPreview = document.createElement("icon-preview");
        this.shadowRoot.getElementById("svg-preview-container").appendChild(this.iconPreview);
        this.iconPreview.addEventListener("save-design", (ev) => {
            this.saveDesign(ev.detail);
        });
        this.updateIconControls();

        this.shadowRoot.getElementById("search-input").addEventListener("input", (ev) => {
            this.state.filterText = ev.target.value;
            this.filterIcons();
        });

        this.shadowRoot.getElementById("svg-container").addEventListener("click", this.createDelegatedEventListener("button", (ev) => {
            this.selectedIconButton = ev.target.closest("button");
            this.setSelectedIcon();
        }));

        this.shadowRoot.getElementById("from-color").addEventListener("input", (ev) => {
            this.state.fromColor = ev.target.value;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("to-color").addEventListener("input", (ev) => {
            this.state.toColor = ev.target.value;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("from-background-color").addEventListener("input", (ev) => {
            this.state.fromBackgroundColor = ev.target.value;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("to-background-color").addEventListener("input", (ev) => {
            this.state.toBackgroundColor = ev.target.value;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("include-icon").addEventListener("change", (ev) => {
            this.state.includeIcon = ev.target.checked;
            this.updateIconControls();
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("icon-text").addEventListener("input", (ev) => {
            this.state.text = ev.target.value;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("text-font").addEventListener("change", (ev) => {
            this.state.fontFamily = ev.target.value;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("text-size").addEventListener("input", (ev) => {
            const input = ev.target;
            const size = Math.min(Number(input.max), Math.max(Number(input.min), Number(input.value) || 6));
            this.state.fontSize = size;
            input.value = size;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("text-bold").addEventListener("change", (ev) => {
            this.state.bold = ev.target.checked;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("text-italic").addEventListener("change", (ev) => {
            this.state.italic = ev.target.checked;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("zoom").addEventListener("input", (ev) => {
            this.state.zoom = parseInt(ev.target.value);
            this.shadowRoot.getElementById("zoom-label").textContent = `${this.state.zoom}%`;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("background-items").addEventListener("change", this.createDelegatedEventListener("input", (ev) => {
            this.state.backgroundShape = ev.target.closest("input").id;
            this.updateBackgroundColorControls();
            this.renderIconPreview();
        }));

        this.shadowRoot.getElementById("symbol-set-select").addEventListener("change", (ev) => {
            this.state.iconSet = ev.target.value;
            this.filterIcons();
        });

        this.shadowRoot.getElementById("enable-gradient").addEventListener("change", (ev) => {
            this.state.enableGradient = ev.target.checked;
            this.shadowRoot.querySelectorAll(".gradient-controls").forEach(c => c.classList.toggle("hidden", !this.state.enableGradient));
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("enable-background-gradient").addEventListener("change", (ev) => {
            this.state.enableBackgroundGradient = ev.target.checked;
            this.updateBackgroundColorControls();
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("reset-zoom").addEventListener("click", (ev) => {
            this.state.zoom = 100;
            this.shadowRoot.getElementById("zoom").value = this.state.zoom;
            this.shadowRoot.getElementById("zoom-label").textContent = `${this.state.zoom}%`;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("rotation-angle").addEventListener("input", (ev) => {
            this.state.rotationAngle = parseInt(ev.target.value);
            this.shadowRoot.getElementById("rotation-angle-label").textContent = `${this.state.rotationAngle}°`;
            this.renderIconPreview();
        });

        this.shadowRoot.getElementById("reset-rotation-angle").addEventListener("click", (ev) => {
            this.state.rotationAngle = 0;
            this.shadowRoot.getElementById("rotation-angle").value = this.state.rotationAngle;
            this.shadowRoot.getElementById("rotation-angle-label").textContent = `${this.state.rotationAngle}°`;
            this.renderIconPreview();
        });

        let index = this.icons.findIndex(x => x.title === this.defaultIconTitle);
        this.selectedIconButton = this.shadowRoot.querySelectorAll("#svg-container button")[index];
        this.setSelectedIcon();
        this.loadSavedDesigns();
    }

    updateBackgroundColorControls() {
        const enableBackgroundColor = this.state.backgroundShape !== "no-background";
        const enableBackgroundGradient = this.state.enableBackgroundGradient;

        this.shadowRoot.querySelectorAll(".background-color-control").forEach(c => c.classList.toggle("hidden", !enableBackgroundColor));
        this.shadowRoot.querySelectorAll(".background-gradient-controls").forEach(c => c.classList.toggle("hidden", !enableBackgroundGradient || !enableBackgroundColor));
    }

    updateIconControls() {
        const iconLibrary = this.shadowRoot.querySelector(".icon-library-panel");
        iconLibrary.classList.toggle("is-disabled", !this.state.includeIcon);
        iconLibrary.setAttribute("aria-disabled", String(!this.state.includeIcon));
        iconLibrary.inert = !this.state.includeIcon;
        this.shadowRoot.querySelectorAll(".icon-only-control").forEach(control => {
            control.classList.toggle("hidden", !this.state.includeIcon);
        });
    }

    loadSavedDesigns() {
        const status = this.shadowRoot.getElementById("saved-design-status");
        try {
            const storedDesigns = localStorage.getItem(this.savedDesignStorageKey);
            const designs = storedDesigns ? JSON.parse(storedDesigns) : [];
            if (!Array.isArray(designs) || designs.some(design => !this.isValidSavedDesign(design))) {
                throw new Error("The saved designs data is invalid.");
            }
            this.savedDesigns = designs;
            this.renderSavedDesigns();
        } catch (error) {
            this.savedDesignsLoadError = error;
            status.textContent = `Saved designs could not be loaded: ${error.message}`;
        }
    }

    isValidSavedDesign(design) {
        const state = design?.state;
        const colors = [
            state?.fromColor, state?.toColor, state?.fromBackgroundColor, state?.toBackgroundColor
        ];
        return typeof design?.id === "string" && typeof design.svg === "string" &&
            state && typeof state.text === "string" && state.text.length <= 32 &&
            typeof state.includeIcon === "boolean" && typeof state.bold === "boolean" &&
            typeof state.italic === "boolean" && typeof state.enableGradient === "boolean" &&
            typeof state.enableBackgroundGradient === "boolean" &&
            ["Arial, sans-serif", "Georgia, serif", "Verdana, sans-serif", "'Trebuchet MS', sans-serif",
                "'Courier New', monospace"].includes(state.fontFamily) &&
            Number.isFinite(state.fontSize) && state.fontSize >= 6 && state.fontSize <= 72 &&
            Number.isFinite(state.zoom) && state.zoom >= 10 && state.zoom <= 200 &&
            Number.isFinite(state.rotationAngle) && state.rotationAngle >= -180 && state.rotationAngle <= 180 &&
            ["no-background", "rounded-square-background", "circle-background", "square-background"]
                .includes(state.backgroundShape) &&
            colors.every(color => typeof color === "string" && /^#[0-9a-f]{6}$/i.test(color)) &&
            typeof state.selectedIcon?.title === "string" && typeof state.selectedIcon?.set === "string";
    }

    saveDesign(detail) {
        const status = this.shadowRoot.getElementById("saved-design-status");
        if (this.savedDesignsLoadError) {
            status.textContent = `Design could not be saved because existing saved designs could not be read: ${this.savedDesignsLoadError.message}`;
            return;
        }
        const designState = { ...detail.state };
        const selectedIcon = designState.selectedIcon;
        delete designState.filterText;
        delete designState.iconSet;
        designState.selectedIcon = { title: selectedIcon.title, set: selectedIcon.set };
        const entry = {
            id: `${Date.now().toString(36)}-${Math.random().toString(36).slice(2)}`,
            state: designState,
            svg: detail.svg
        };

        try {
            const designs = [...this.savedDesigns, entry];
            localStorage.setItem(this.savedDesignStorageKey, JSON.stringify(designs));
            this.savedDesigns = designs;
            this.renderSavedDesigns();
            status.textContent = "Design saved in this browser.";
        } catch (error) {
            status.textContent = `Design could not be saved: ${error.message}`;
        }
    }

    renderSavedDesigns() {
        const list = this.shadowRoot.getElementById("saved-design-list");
        list.replaceChildren();
        this.savedDesigns.forEach((design, index) => {
            const name = design.state.text.trim() ||
                `${design.state.selectedIcon.title} by ${design.state.selectedIcon.set}`;
            const item = document.createElement("div");
            item.className = "saved-design-item";

            const loadButton = document.createElement("button");
            loadButton.className = "saved-design-load";
            loadButton.type = "button";
            loadButton.setAttribute("aria-label", `Load saved design: ${name}`);
            const thumbnail = document.createElement("img");
            thumbnail.alt = "";
            thumbnail.src = `data:image/svg+xml;charset=utf-8,${encodeURIComponent(design.svg)}`;
            loadButton.append(thumbnail);
            loadButton.addEventListener("click", () => this.loadDesign(index));

            const caption = document.createElement("p");
            caption.textContent = name;
            caption.title = name;

            const removeButton = document.createElement("button");
            removeButton.className = "saved-design-remove";
            removeButton.type = "button";
            removeButton.textContent = "Remove";
            removeButton.setAttribute("aria-label", `Remove saved design: ${name}`);
            removeButton.addEventListener("click", () => this.removeSavedDesign(index));

            item.append(loadButton, caption, removeButton);
            list.append(item);
        });
    }

    loadDesign(index) {
        const design = this.savedDesigns[index];
        const selectedIcon = this.icons.find(icon =>
            icon.title === design.state.selectedIcon.title && icon.set === design.state.selectedIcon.set);
        if (!selectedIcon) {
            this.shadowRoot.getElementById("saved-design-status").textContent =
                "This saved design refers to an icon that is no longer available.";
            return;
        }

        this.state = { ...this.state, ...design.state, selectedIcon };
        this.selectedIconButton = Array.from(this.shadowRoot.querySelectorAll("#svg-container button"))
            .find(button => Number(button.dataset.index) === this.icons.indexOf(selectedIcon));
        this.shadowRoot.querySelectorAll("#svg-container button").forEach(button => {
            button.setAttribute("aria-pressed", String(button === this.selectedIconButton));
        });

        this.shadowRoot.getElementById("include-icon").checked = this.state.includeIcon;
        this.shadowRoot.getElementById("icon-text").value = this.state.text;
        this.shadowRoot.getElementById("text-font").value = this.state.fontFamily;
        this.shadowRoot.getElementById("text-size").value = this.state.fontSize;
        this.shadowRoot.getElementById("text-bold").checked = this.state.bold;
        this.shadowRoot.getElementById("text-italic").checked = this.state.italic;
        this.shadowRoot.getElementById("from-color").value = this.state.fromColor;
        this.shadowRoot.getElementById("to-color").value = this.state.toColor;
        this.shadowRoot.getElementById("enable-gradient").checked = this.state.enableGradient;
        this.shadowRoot.getElementById("from-background-color").value = this.state.fromBackgroundColor;
        this.shadowRoot.getElementById("to-background-color").value = this.state.toBackgroundColor;
        this.shadowRoot.getElementById("enable-background-gradient").checked = this.state.enableBackgroundGradient;
        this.shadowRoot.querySelectorAll("#background-items input").forEach(input => {
            input.checked = input.id === this.state.backgroundShape;
        });
        this.shadowRoot.getElementById("zoom").value = this.state.zoom;
        this.shadowRoot.getElementById("zoom-label").textContent = `${this.state.zoom}%`;
        this.shadowRoot.getElementById("rotation-angle").value = this.state.rotationAngle;
        this.shadowRoot.getElementById("rotation-angle-label").textContent = `${this.state.rotationAngle}°`;
        this.shadowRoot.querySelectorAll(".gradient-controls").forEach(control => {
            control.classList.toggle("hidden", !this.state.enableGradient);
        });
        this.updateBackgroundColorControls();
        this.updateIconControls();
        this.filterIcons();
        this.renderIconPreview();
        this.shadowRoot.getElementById("saved-design-status").textContent = `Loaded “${this.state.text || selectedIcon.title}”.`;
    }

    removeSavedDesign(index) {
        const status = this.shadowRoot.getElementById("saved-design-status");
        try {
            const designs = this.savedDesigns.filter((design, designIndex) => designIndex !== index);
            localStorage.setItem(this.savedDesignStorageKey, JSON.stringify(designs));
            this.savedDesigns = designs;
            this.renderSavedDesigns();
            status.textContent = "Saved design removed.";
        } catch (error) {
            status.textContent = `Saved design could not be removed: ${error.message}`;
        }
    }

    filterIcons() {
        const text = this.state.filterText ? this.state.filterText.toLowerCase() : null;
        const iconSet = this.state.iconSet;
        const iconButtons = this.shadowRoot.querySelectorAll("#svg-container button");
        let hasHits = false;
        for (let i = 0; i < this.icons.length; i++) {
            if ((!text || this.icons[i].title.toLowerCase().includes(text)) && (!iconSet || this.icons[i].set === iconSet)) {
                iconButtons[i].classList.remove("hidden");
                hasHits = true;
            } else {
                iconButtons[i].classList.add("hidden");
            }
        }

        this.shadowRoot.getElementById("no-hits").classList.toggle("hidden", hasHits);
    }

    setSelectedIcon() {
        const currentSelection = this.shadowRoot.querySelector("#svg-container button[aria-pressed='true']");
        if (currentSelection) {
            currentSelection.setAttribute("aria-pressed", "false");
        }

        this.selectedIconButton.setAttribute("aria-pressed", "true");
        this.state.selectedIcon = this.icons[Number(this.selectedIconButton.dataset.index)];
        this.renderIconPreview();
    }

    renderIconPreview() {
        this.iconPreview.render(this.state);

        const preview = this.iconPreview.shadowRoot.querySelector(".preview-container > svg");
        this.observedPreview = preview;

        const dock = this.shadowRoot.getElementById("preview-dock");
        const dockIcon = preview.cloneNode(true);
        dockIcon.setAttribute("aria-hidden", "true");
        dockIcon.setAttribute("focusable", "false");
        dock.querySelector(".preview-dock-canvas").replaceChildren(dockIcon);
        const description = [
            this.state.includeIcon ? `${this.state.selectedIcon.title} by ${this.state.selectedIcon.set}` : "",
            this.state.text
        ].filter(Boolean).join(" and ");
        dock.setAttribute("aria-label", `Floating preview of ${description || "the current design"}`);
        this.updatePreviewDockVisibility();
    }

    createDelegatedEventListener(selector, handler) {
        return function (event) {
            if (event.target.matches(selector) || event.target.closest(selector)) {
                handler(event);
            }
        }
    }
}

customElements.define('icon-maker', IconMaker);
