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
    display: flex;
    flex-direction: column;
}
#bottom-controls h3 {
    font-weight: normal;
    margin: 25px 0 10px;
}
#bottom-controls h3:first-child {
    margin-top: 0;
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
.spacer {
    margin-bottom: 15px;
}
#reset-zoom {
    color: #da4526;
    border: 0;
    background-color: transparent;
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

    <h3>Color</h3>
    <input type="color" id="from-color" value="${this.state.fromColor}" />
  
    <div class="spacer"></div>

    <div>
      <input type="checkbox" id="enable-gradient" />
      <label for="enable-gradient">Enable Gradient</label>
    </div>

    <h3 class="gradient-controls ${this.state.enableGradient ? "" : "hidden"}">Color 2</h3>
    <div class="gradient-controls ${this.state.enableGradient ? "" : "hidden"}">
      <input type="color" id="to-color" value="${this.state.toColor}" />
    </div>
    
    <h3>Background Shape</h3>
    <ul id="background-items">
      <li><input name="backgrounds" type="radio" id="no-background" checked><label for="no-background">None</label></li>
      <li><input name="backgrounds" type="radio" id="rounded-square-background"><label for="rounded-square-background">Rounded Square</label></li>
      <li><input name="backgrounds" type="radio" id="circle-background"><label for="circle-background">Circle</label></li>
      <li><input name="backgrounds" type="radio" id="square-background"><label for="square-background">Square</label></li>
    </ul>

    <h3 class="background-color-control hidden">Background Color</h3>
    <input class="background-color-control hidden" type="color" id="from-background-color" value="${this.state.fromBackgroundColor}" style="grid-column: 2;" />

    <div class="background-color-control hidden">
      <div class="spacer"></div>
      <input type="checkbox" id="enable-background-gradient" />
      <label for="enable-background-gradient">Enable Gradient</label>
    </div>

    <h3 class="background-color-control background-gradient-controls ${this.state.enableBackgroundGradient ? "" : "hidden"}">Background Color 2</h3>
    <div class="background-color-control background-gradient-controls ${this.state.enableBackgroundGradient ? "" : "hidden"}">
      <input type="color" id="to-background-color" value="${this.state.toBackgroundColor}" />
    </div>

    <h3 for="zoom">Zoom</h3>
    <div>
      <input type="range" id="zoom" min="10" max="200" value="${this.state.zoom}" />
      <button id="reset-zoom"><svg viewBox="0 0 32 32" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" class="selected"><path fill="currentColor" stroke="none" d="M16 2c-4.418 0-8.418 1.791-11.313 4.687l-4.686-4.687v12h12l-4.485-4.485c2.172-2.172 5.172-3.515 8.485-3.515 6.627 0 12 5.373 12 12 0 3.584-1.572 6.801-4.063 9l2.646 3c3.322-2.932 5.417-7.221 5.417-12 0-8.837-7.163-16-16-16z"></path></svg></button>
    </div>
    <div>
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
            this.filterIcons();
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

        this.shadowRoot.getElementById("reset-zoom").addEventListener("click", (ev) => {
            this.state.zoom = 100;
            this.shadowRoot.getElementById("zoom").value = this.state.zoom;
            this.shadowRoot.getElementById("zoom-label").innerHTML = `${this.state.zoom}%`;
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
                hasHits = true;
            } else {
                svgs[i].classList.add("hidden");
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
