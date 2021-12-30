import { Icons } from './Icons.js';
import { IconPreview } from './IconPreview.js';

class IconMaker extends HTMLElement {
    state = {
        filterText: null,
        iconSet: null,
        selectedIcon: null,
        backgroundShape: null,
        fromColor: "#da4526",
        toColor: "#da4526",
        fromBackgroundColor: "#333333",
        toBackgroundColor: "#333333",
        zoom: 100,
        enableGradient: false,
        enableBackgroundGradient: false
    };

    defaultColor = "#da4526";
    selectedColor = "orange";
    defaultIconTitle = "tree";
    selectedSvg = null;
    iconPreview = null;

    icons = null;

    styles = `
#svg-container {
    margin: 25px 0;
    max-height: 300px;
    overflow-y: scroll;
}
#svg-container svg {
    padding: 2px 4px;
}
#bottom-area {
    display: flex; 
    justify-content: space-between; 
    flex-wrap: nowrap;
}
#bottom-controls {
    display: grid;
    height: min-content;
    gap: 25px 10px;
}
input[type=text] {
    font-size: inherit;
    border: 1px solid #ccc;
    padding: 4px;
    background-color: #eee;
}
ul {
    list-style-type: none; 
    padding: 0; 
    margin: 0;
}
.gradient-controls, .background-color-control, .background-gradient-controls {
    transition: opacity 0.8s;
}
.hidden {
    visibility: hidden;
    opacity: 0;
    position: absolute;
    transition: none;
}
svg.selected {
    background-color: orange;
}
svg.selected path {
    fill: black;
}
`;

    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
    }

    async connectedCallback() {
        this.render();
    }

    disconnectedCallback() {
    }

    async render() {
        this.icons = Icons.getIcons();
        let markup = "";
        markup += `
<style>
${this.styles}
</style>
<input type="text" placeholder="Search ${this.icons.length} icons" id="search-input" />

<h4>Icon Set Filter</h4>
<ul id="symbol-set-items">
  <li><input name="symbol-set-radio" type="radio" id="allSymbolSets" value="" checked><label for="allSymbolSets">All</label></li>
  <li><input name="symbol-set-radio" type="radio" id="Afiado0" value="Afiado"><label for="Afiado0">Afiado</label></li>
  <li><input name="symbol-set-radio" type="radio" id="Entypo+1" value="Entypo+"><label for="Entypo+1">Entypo+</label></li>
  <li><input name="symbol-set-radio" type="radio" id="Feather2" value="Feather"><label for="Feather2">Feather</label></li>
  <li><input name="symbol-set-radio" type="radio" id="IcoMoon - Free3" value="IcoMoon - Free"><label for="IcoMoon - Free3">IcoMoon - Free</label></li>
  <li><input name="symbol-set-radio" type="radio" id="Simple Icons4" value="Simple Icons"><label for="Simple Icons4">Simple Icons</label></li>
</ul>

<p id="no-hits" class="hidden">No matching icons found.</p>

<div id="svg-container">`;
        this.icons.forEach((icon, index) => {
            markup += `<svg data-index=${index} viewBox="0 0 32 32" height="32" width="32" xmlns="http://www.w3.org/2000/svg"><path fill="${this.defaultColor}" stroke="none" d="${icon.path}"></path></svg>`;
        });
        markup += `
</div>

<div id="bottom-area">
  <div id="bottom-controls">
    <label for="from-color"style="grid-column: 1;">Color</label>
    <input type="color" id="from-color" value="${this.state.fromColor}" style="grid-column: 2;" />
    
    <div style="grid-column: 1 / span 2;">
      <input type="checkbox" id="enable-gradient" />
      <label for="enable-gradient">Enable Gradient</label>
    </div>
    
    <label class="gradient-controls ${this.state.enableGradient ? "" : "hidden"}" for="to-color" style="grid-column: 1;">Color 2</label>
    <div class="gradient-controls ${this.state.enableGradient ? "" : "hidden"}" style="grid-column: 2;">
      <input type="color" id="to-color" value="${this.state.toColor}" />
    </div>
    
    <label style="grid-column: 1;">Background Shape</label>
    <ul style="grid-column: 2;" id="background-items">
      <li><input name="backgrounds" type="radio" id="no-background" checked><label for="no-background">None</label></li>
      <li><input name="backgrounds" type="radio" id="rounded-square-background"><label for="rounded-square-background">Rounded Square</label></li>
      <li><input name="backgrounds" type="radio" id="circle-background"><label for="circle-background">Circle</label></li>
      <li><input name="backgrounds" type="radio" id="square-background"><label for="square-background">Square</label></li>
    </ul>

    <label class="background-color-control hidden" for="from-background-color"style="grid-column: 1;">Background Color</label>
    <input class="background-color-control hidden" type="color" id="from-background-color" value="${this.state.fromBackgroundColor}" style="grid-column: 2;" />

    <div style="grid-column: 1 / span 2;" class="background-color-control hidden">
      <input type="checkbox" id="enable-background-gradient" />
      <label for="enable-background-gradient">Enable Gradient</label>
    </div>

    <label class="background-color-control background-gradient-controls ${this.state.enableBackgroundGradient ? "" : "hidden"}" for="to-background-color" style="grid-column: 1;">Background Color 2</label>
    <div style="grid-column: 2;" class="background-color-control background-gradient-controls ${this.state.enableBackgroundGradient ? "" : "hidden"}">
      <input type="color" id="to-background-color" value="${this.state.toBackgroundColor}" />
    </div>

    <label for="zoom" style="grid-column: 1;">Zoom</label>
    <div style="grid-column: 2;">
      <input type="range" id="zoom" min="10" max="200" value="${this.state.zoom}" />
      <label id="zoom-label">${this.state.zoom}%</label>
    </div>
  </div>
  <div id="svg-preview-container">
  </div>
</div>
`;

        this.shadowRoot.innerHTML = markup;

        this.iconPreview = document.createElement("icon-preview");
        this.shadowRoot.getElementById("svg-preview-container").appendChild(this.iconPreview);

        this.shadowRoot.getElementById("search-input").addEventListener("input", (ev) => {
            this.state.filterText = ev.target.value;
            this.filterIcons();
        });

        this.shadowRoot.getElementById("svg-container").addEventListener("click", this.createDelegatedEventListener("svg", (ev) => {
            this.selectedSvg = ev.target.closest("svg");
            this.setSelectedSvg();
        }));

        this.shadowRoot.getElementById("from-color").addEventListener("input", (ev) => {
            this.state.fromColor = ev.target.value;
            this.iconPreview.render(this.state);
        });

        this.shadowRoot.getElementById("to-color").addEventListener("input", (ev) => {
            this.state.toColor = ev.target.value;
            this.iconPreview.render(this.state);
        });

        this.shadowRoot.getElementById("from-background-color").addEventListener("input", (ev) => {
            this.state.fromBackgroundColor = ev.target.value;
            this.iconPreview.render(this.state);
        });

        this.shadowRoot.getElementById("to-background-color").addEventListener("input", (ev) => {
            this.state.toBackgroundColor = ev.target.value;
            this.iconPreview.render(this.state);
        });

        this.shadowRoot.getElementById("zoom").addEventListener("input", (ev) => {
            this.state.zoom = parseInt(ev.target.value);
            this.shadowRoot.getElementById("zoom-label").innerHTML = `${this.state.zoom}%`;
            this.iconPreview.render(this.state);
        });

        this.shadowRoot.getElementById("background-items").addEventListener("click", this.createDelegatedEventListener("input", (ev) => {
            this.state.backgroundShape = ev.target.closest("input").id;
            this.updateBackgroundColorControls();
            this.iconPreview.render(this.state);
        }));

        this.shadowRoot.getElementById("symbol-set-items").addEventListener("click", this.createDelegatedEventListener("input", (ev) => {
            this.state.iconSet = ev.target.closest("input").value;
            this.iconPreview.render(this.state);
        }));

        this.shadowRoot.getElementById("enable-gradient").addEventListener("click", (ev) => {
            this.state.enableGradient = ev.target.checked;
            this.shadowRoot.querySelectorAll(".gradient-controls").forEach(c => c.classList.toggle("hidden"), !this.state.enableGradient);
            this.iconPreview.render(this.state);
        });

        this.shadowRoot.getElementById("enable-background-gradient").addEventListener("click", (ev) => {
            this.state.enableBackgroundGradient = ev.target.checked;
            this.updateBackgroundColorControls();
            this.iconPreview.render(this.state);
        });

        let index = this.icons.findIndex(x => x.title === this.defaultIconTitle);
        this.selectedSvg = this.shadowRoot.querySelectorAll("#svg-container svg")[index];
        this.setSelectedSvg();
    }

    updateBackgroundColorControls() {
        const enableBackgroundColor = this.state.backgroundShape !== "no-background";
        const enableBackgroundGradient = this.state.enableBackgroundGradient;

        this.shadowRoot.querySelectorAll(".background-color-control").forEach(c => c.classList.toggle("hidden", !enableBackgroundColor));
        this.shadowRoot.querySelectorAll(".background-gradient-controls").forEach(c => c.classList.toggle("hidden", !enableBackgroundGradient));
    }

    filterIcons() {
        const text = this.state.filterText ? this.state.filterText.toLowerCase() : null;
        const iconSet = this.state.iconSet;
        const svgs = this.shadowRoot.querySelectorAll("#svg-container svg");
        let hasHits = false;
        for (let i = 0; i < this.icons.length; i++) {
            if ((!text || this.icons[i].title.indexOf(text) >= 0) && (!iconSet || this.icons[i].set === iconSet)) {
                svgs[i].classList.remove("hidden");
            } else {
                svgs[i].classList.add("hidden");
                hasHits = true;
            }
        }

        this.shadowRoot.getElementById("no-hits").classList.toggle("hidden", hasHits);
    }

    setSelectedSvg() {
        const currentSelection = this.shadowRoot.querySelector("svg.selected")
        if (currentSelection) {
            currentSelection.classList.remove("selected");
        }

        this.selectedSvg.classList.add("selected");
        this.state.selectedIcon = this.icons[parseInt(this.selectedSvg.attributes["data-index"].value)];
        this.iconPreview.render(this.state);
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
