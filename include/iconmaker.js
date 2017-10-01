var clickRectangle = null;
function createIcons() {
    var fill = {fill: "#333", stroke: "none"},
        selected,
        none = {fill: "#000", opacity: 0};

	var clickableIcons = [];

	var iconsArray = [];
	$.each(emnet.icons, function(i, icon) {
		iconsArray.push({ name: icon.title, path: icon.path, set: icon.set });
	});

	$.each(iconsArray, function(i, element) {
		var name = element.name;
		var path = element.path;
		var set = element.set;
		var containerElement = $("#symbols");
		var iconContainer = document.createElement("div");
		iconContainer.setAttribute("data-bind", 'visible: shouldDisplaySymbol("' + name + '")')
    iconContainer.setAttribute("title", name);
		containerElement.append(iconContainer);

		var r = Raphael(iconContainer, 32, 32);
		var pathObject = r.path(path).attr(fill)

		var rectangle = r.rect(0, 0, 32, 32).attr(none);
		rectangle.click(function () {
			selected && selected.attr(fill);

			selected = pathObject.attr({fill: "90-#EDAF02-#FF5E00"});
			selected = pathObject;
			createIcon(path, false, name);
		});

		clickableIcons.push({name: name, set: set, clickFunction: rectangle.events[0].f});
	});

	return clickableIcons;
}

var icons = [];
var paper;
var cachedIconPathData;
var iconmakerViewModel;

function IconmakerViewModel() {

	var self = this;

	// functions to select an icon
	self.clickableIcons = null;

	// named icons with functions how to select them
	self.icons = null;

	self.getValidIntegerValue = function(value, minValue, maxValue, defaultValue) {
		if (!isNaN(value)) {
			var valueAsInt = parseInt(value);
			return Math.max(Math.min(maxValue, valueAsInt), minValue);
		}

		return defaultValue;
	};

	self.isValidIntegerValue = function(value, minValue, maxValue) {
		if (!isNaN(value)) {
			var valueAsInt = parseInt(value);
			if(isNaN(valueAsInt)) {
				return false;
			}

			return valueAsInt <= maxValue && valueAsInt >= minValue;
		}

		return false;
	};

	ko.extenders.numeric = function(target, options) {

		// parse the options object and extract properties
		var options = JSON.parse(options);
		var min = options.min;
		var max = options.max;
		var defaultValue = options.defaultValue;

		//create a writeable computed observable to intercept writes to our observable
		var result = ko.computed({
			read: target,  //always return the original observables value
			write: function(newValue) {

			if(!self.isValidIntegerValue(newValue, min, max)) {
				return;
			}

				var current = target();
				var valueToWrite = parseInt(newValue);

				//only write if it changed
				if (valueToWrite !== current) {
					target(valueToWrite);
				} else {
					//if the rounded value is the same, but a different value was written, force a notification for the current field
					if (newValue !== current) {
						target.notifySubscribers(valueToWrite);
					}
				}
			}
		});

		//initialize with current value to make sure it is rounded appropriately
		result(target());

		//return the new computed observable
		return result;
	};

	self.selectedIconName = ko.observable();

	self.fontSize = ko.observable(37).extend({ numeric: JSON.stringify({ min: 8, max: 300, defaultValue: 37 }) });

	self.textRotation = ko.observable(0).extend({ numeric: JSON.stringify({ min: 0, max: 360, defaultValue: 0 }) });

	self.textXPos = ko.observable(0).extend({ numeric: JSON.stringify({ min: -100, max: 100, defaultValue: 0 }) });

	self.textYPos = ko.observable(0).extend({ numeric: JSON.stringify({ min: -100, max: 100, defaultValue: 0 }) });

	self.iconText = ko.observable('Sample');

	self.shapeType = ko.observable('roundedSquare');

	self.iconBackFromColor = ko.observable('00dbcf');

	self.iconBackToColor = ko.observable('0039ff');

	self.iconFromColor = ko.observable('ffffff');

  self.iconFromColor.subscribe(function(newValue) {
    if(self.mode() === 'simple') {
      self.iconToColor(newValue);
      self.refreshColors();
    }
  })

	self.iconToColor = ko.observable('cccccc');

	self.symbolName = ko.observable();

	self.symbolSets = ko.observableArray();

  self.selectedSymbolSet = ko.observable(null);

	self.shouldDisplaySymbol = function (name) {
		var icon = $.grep(self.clickableIcons, function (icon) {
			return icon.name === name;
		})[0];

		if(!icon) {
			return false;
		}

		var set = $.grep(self.symbolSets(), function(set) {
			return (!self.selectedSymbolSet() || set.id === self.selectedSymbolSet()) && set.name == icon.set;
		});

		if (!set.length) {
			return false;
		}

		return !self.symbolName() || name.toLowerCase().indexOf(self.symbolName().toLowerCase()) != -1;
	}

	var availableFonts = ["Trebuchet MS", "Verdana", "Arial", "Times New Roman"]
	self.fonts = ko.observableArray(availableFonts);
	self.iconFontFamily = ko.observable(availableFonts[0]);

	self.bold = ko.observable(false);

	self.fileType = ko.observable('png');

	self.svgSupported = ko.observable(true);

	self.iconsize_backingfield = ko.observable(128);
	self.iconsize = ko.computed({
		read: function() {
			return self.iconsize_backingfield();
		},
		write: function(value) {
			self.iconsize_backingfield(self.getValidIntegerValue(value, 8, 1024, 128));
		}
	});

  self.displayIconName = ko.computed(function() {
    var name = self.selectedIconName() || 'icon';
    return name[0].toUpperCase() + name.substring(1, name.length);
  });

	self.shiny = ko.observable(true);

	self.glow = ko.observable(false);
	self.glowWidth = ko.observable(10).extend({ numeric: JSON.stringify({ min: 0, max: 50, defaultValue: 10 }) });
	self.glowOffsetX = ko.observable(0).extend({ numeric: JSON.stringify({ min: -30, max: 30, defaultValue: 0 }) });
	self.glowOffsetY = ko.observable(0).extend({ numeric: JSON.stringify({ min: -30, max: 30, defaultValue: 0 }) });
	self.glowColor = ko.observable('000000');

	self.iconType = ko.observable('icon');

	self.symbolSize = ko.observable(100).extend({ numeric: JSON.stringify({ min: 1, max: 300, defaultValue: 10 }) });

	self.symbolXPos = ko.observable(0).extend({ numeric: JSON.stringify({ min: -100, max: 100, defaultValue: 0 }) });

	self.symbolYPos = ko.observable(0).extend({ numeric: JSON.stringify({ min: -100, max: 100, defaultValue: 0 }) });

	self.symbolRotation = ko.observable(0).extend({ numeric: JSON.stringify({ min: 0, max: 360, defaultValue: 0 }) });

	self.glowOpacity = ko.observable(50).extend({ numeric: JSON.stringify({ min: 0, max: 100, defaultValue: 50 }) });

  self.mode = ko.observable('simple');

  self.mode.subscribe(function (newValue) {
    if(newValue === 'simple') {
      var settings = {
  			shapeType: 'none',
        iconToColor: self.iconFromColor(),
        shiny: false,
        glow: false,
        iconType: 'icon',
        symbolSize: 100,
      	symbolXPos: 0,
      	symbolYPos: 0,
      	symbolRotation: 0,
      	glow: false,
        shapeType: 'none',
        mode: 'simple'
      }

      self.setSettings(settings);
    }
  });

	// saving and loading

	var locallySavedPresetsStr = localStorage.getItem("savedPresets");
	var locallySavedPresets = [];
	if (locallySavedPresetsStr) {
		try {
			locallySavedPresets = JSON.parse(locallySavedPresetsStr);
			if (!($.isArray(locallySavedPresets) || locallySavedPresets.length > 5)) {
				locallySavedPresets = [];
				localStorage.removeItem("savedPresets");
			}
		}
		catch(e) {
			localStorage.removeItem("savedPresets");
		}
	}

	self.savedPresets = ko.observableArray(locallySavedPresets);

	self.addPreset = function() {

		createIcon(cachedIconPathData,true, null, 96);
		var imageData = document.getElementById("canvas").toDataURL("image/png");
		createIcon(cachedIconPathData,false);

		self.savedPresets.push({
			imageData: imageData,
			selectedIconName: self.selectedIconName(),
			fontSize: self.fontSize(),
			textRotation: self.textRotation(),
			textXPos: self.textXPos(),
			textYPos: self.textYPos(),
			iconText: self.iconText(),
			shapeType: self.shapeType(),
			iconBackFromColor: self.iconBackFromColor(),
			iconBackToColor: self.iconBackToColor(),
			iconFromColor: self.iconFromColor(),
			iconToColor: self.iconToColor(),
			iconFontFamily: self.iconFontFamily(),
			bold: self.bold(),
			fileType: self.fileType(),
			iconsize: self.iconsize_backingfield(),
			shiny: self.shiny(),
			glow: self.glow(),
			glowWidth: self.glowWidth(),
			glowOffsetX: self.glowOffsetX(),
			glowOffsetY: self.glowOffsetY(),
			glowColor: self.glowColor(),
			iconType: self.iconType(),
			symbolSize: self.symbolSize(),
			symbolXPos: self.symbolXPos(),
			symbolYPos: self.symbolYPos(),
			symbolRotation: self.symbolRotation(),
			glowOpacity: self.glowOpacity()
		})

		localStorage.setItem("savedPresets", JSON.stringify(self.savedPresets()));
	};

	self.setSettings = function(settings) {

		var defVal = function(value, defaultValue) {
			if(value === null || value === undefined) {
				return defaultValue;
			}
			return value;
		};

    var selectedIconName = defVal(settings.selectedIconName, self.selectedIconName());
		self.selectedIconName(selectedIconName);
		self.fontSize(defVal(settings.fontSize, self.fontSize()));
		self.textRotation(defVal(settings.textRotation, self.textRotation()));
		self.textXPos(defVal(settings.textXPos, self.textXPos()));
		self.textYPos(defVal(settings.textYPos, self.textYPos()));
		self.iconText(defVal(settings.iconText, self.iconText()));
		self.shapeType(defVal(settings.shapeType, self.shapeType()));
		self.iconBackFromColor(defVal(settings.iconBackFromColor, self.iconBackFromColor()));
		self.iconBackToColor(defVal(settings.iconBackToColor, self.iconBackToColor()));
		self.iconFromColor(defVal(settings.iconFromColor, self.iconFromColor()));
		self.iconToColor(defVal(settings.iconToColor, self.iconToColor()));
		self.iconFontFamily(defVal(settings.iconFontFamily, self.iconFontFamily()));
		self.bold(defVal(settings.bold, self.bold()));
		self.fileType(defVal(settings.fileType, self.fileType()));
		self.iconsize_backingfield(defVal(settings.iconsize, self.iconsize_backingfield()));
		self.shiny(defVal(settings.shiny, self.shiny()));
		self.glow(defVal(settings.glow, self.glow()));
		self.glowWidth(defVal(settings.glowWidth, self.glowWidth()));
		self.glowOffsetX(defVal(settings.glowOffsetX, self.glowOffsetX()));
		self.glowOffsetY(defVal(settings.glowOffsetY, self.glowOffsetY()));
		self.glowColor(defVal(settings.glowColor, self.glowColor()));
		self.iconType(defVal(settings.iconType, self.iconType()));
		self.symbolSize(defVal(settings.symbolSize, self.symbolSize()));
		self.symbolXPos(defVal(settings.symbolXPos, self.symbolXPos()));
		self.symbolYPos(defVal(settings.symbolYPos, self.symbolYPos()));
		self.symbolRotation(defVal(settings.symbolRotation, self.symbolRotation()));
		self.glowOpacity(defVal(settings.glowOpacity, self.glowOpacity()));
    self.mode(defVal(settings.mode, 'advanced'));

		// select the icon
		$.grep(self.clickableIcons, function(icon) { return icon.name == selectedIconName; })[0].clickFunction();

		$('#shapes').buttonset("refresh");

		self.refreshColors();
	};

	self.deletePreset = function (preset) {
		self.savedPresets.remove(preset);
		localStorage.setItem("savedPresets", JSON.stringify(self.savedPresets()));
	};

  self.refreshColors = function () {
    // update colors
		$.each(self.colorPickers, function(i, entry) {
			entry.picker.fromString(entry.observable());
		});
  }

	// saving and loading end

	// color pickers do not update automatically on external change (random icon) so keep references for update later
	self.colorPickers = [];

	var idsAndObservables = [
	{id: 'iconBackFromColor', observable: self.iconBackFromColor},
	{id: 'iconBackToColor', observable: self.iconBackToColor},
	{id: 'iconFromColor', observable: self.iconFromColor},
	{id: 'iconToColor', observable: self.iconToColor},
  {id: 'iconFromAndToColor', observable: self.iconFromColor}];
	$.each(idsAndObservables, function(i, io) {
		var observable = io.observable;
		var id = io.id;
		var picker = new jscolor.color(document.getElementById(id), {});
		picker.fromString(observable());
		self.colorPickers.push({
			observable: observable,
			picker: picker
		});
	});

	var createIconOn = [ self.shapeType, self.iconBackFromColor, self.iconBackToColor, self.iconFromColor, self.iconToColor, self.iconFontFamily, self.iconText, self.bold, self.shiny, self.glow, self.glowWidth, self.glowOffsetX, self.glowOffsetY, self.glowColor, self.iconType, self.symbolSize, self.symbolXPos, self.symbolYPos, self.symbolRotation, self.glowOpacity ];
	$.each(createIconOn, function(i, prop) {
		prop.subscribe(function() {
			if(paper != null) {
				createIcon(cachedIconPathData);
			}
		});
	});

	self.randomize = function() {

		var getColor = function() {
			var getColorComponent = function() {
				return padLeft(Math.floor(Math.random()*256).toString(16).toUpperCase(), 2, '0');
			};
			return getColorComponent() + getColorComponent() + getColorComponent();
		};

		var settings = {
			selectedIconName: self.clickableIcons[Math.floor(Math.random()*(self.clickableIcons.length))].name,
			shapeType: ['roundedSquare', 'sphere', 'square', 'none'][Math.floor(Math.random()*4)], // pick a background
			iconBackFromColor: getColor(),
			iconBackToColor: getColor(),
			iconFromColor: getColor(), // pick icon colors
			iconToColor: getColor(), // pick icon colors
			shiny: Math.floor(Math.random()*2) === 1, // shiny part
			glow: Math.floor(Math.random()*2) === 1, // glow effect
			glowWidth: 10,
			glowOffsetX: 0,
			glowOffsetY: 0,
			glowColor: getColor(),
			iconType: 'icon', // set to use predefined symbols
			symbolSize: 100, // set size to 100%
			symbolXPos: 0,
			symbolYPos: 0,
			symbolRotation: 0,
			glowOpacity: 50,
      mode: 'advanced'
		};

		self.setSettings(settings);
	};
};

ko.bindingHandlers.jqSlider = {
    init: function(element, valueAccessor, allBindingsAccessor) {
        //initialize the control
        var options = allBindingsAccessor().jqOptions || {};
        $(element).slider(options);

		var bindChange = function() {
            var observable = valueAccessor();
            observable($(element).slider("value"));
			if (paper != null) {
				createIcon(cachedIconPathData);
			}
		};

        //handle the value changing in the UI
        ko.utils.registerEventHandler(element, "slide", bindChange);
		ko.utils.registerEventHandler(element, "slidechange", bindChange);

    },
    //handle the model value changing
    update: function(element, valueAccessor) {
        var value = ko.utils.unwrapObservable(valueAccessor());
        $(element).slider("value", value);

    }
};

ko.bindingHandlers.fadeVisible = {
    init: function(element, valueAccessor) {
        // Initially set the element to be instantly visible/hidden depending on the value
        var value = valueAccessor();
        $(element).toggle(ko.utils.unwrapObservable(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
    },
    update: function(element, valueAccessor) {
        // Whenever the value subsequently changes, slowly fade the element in or out
        var value = valueAccessor();
        ko.utils.unwrapObservable(value) ? $(element).fadeIn() : $(element).fadeOut();
    }
};

function downloadIcon() {
	var svg = document.getElementById("icon").innerHTML;
	svg = svg.replace('<div id="svggroup">','').replace('</div>','');

	var icon = $.grep(iconmakerViewModel.clickableIcons, function (icon) {
		return icon.name === iconmakerViewModel.selectedIconName();
	})[0];

	if(!icon) {
		return;
	}

	document.getElementById("downloadIconName").value = icon.name + " (by " + icon.set + ")";

	if(iconmakerViewModel.fileType() == 'svg') {
		if(svg.match(/xmlns/g).length > 1) {
			svg = svg.replace('xmlns="http://www.w3.org/2000/svg"', '');
		}
		document.getElementById("svg").value = svg;
		document.getElementById("svgform").submit();
	}
	else
	{
		downloadPng();
	}
}

function createIcon(iconPathData, renderCanvas, selectedIconName, size) {

	var paperWidth = 138;
	var paperHeight = 138;

	if(!renderCanvas) {
		paper.changeSize(paperWidth, paperHeight, false, false);
	}

	if (selectedIconName) {
		iconmakerViewModel.selectedIconName(selectedIconName);
	}

	var createFromShape = iconmakerViewModel.iconType() == 'icon';

	var shapeType = iconmakerViewModel.shapeType();
	var icon = $.grep(icons, function(icon) { return icon.name == shapeType; })[0];

	cachedIconPathData = iconPathData;
	paper.clear();

	var iconBackFromColor = iconmakerViewModel.iconBackFromColor();
	var iconBackToColor = iconmakerViewModel.iconBackToColor();
	if(icon.iconBackFunction != null) {
		icon.iconBackFunction(paperWidth, paperHeight)
					.attr({fill: "90-#" + iconBackToColor + "-#" + iconBackFromColor, stroke: "none"});
	}

	var iconFromColor = document.getElementById("iconFromColor").value;
	var iconToColor = document.getElementById("iconToColor").value;

	var text = iconmakerViewModel.iconText();
	var fontSize = iconmakerViewModel.fontSize();
	var fontFamily = iconmakerViewModel.iconFontFamily();
	var fontWeight = iconmakerViewModel.bold() ? "bold" : "normal";
	var xpos = iconmakerViewModel.textXPos();
	var ypos = iconmakerViewModel.textYPos();
	var theRotation = iconmakerViewModel.textRotation();
	var iconOnly;
	if(!createFromShape) {
		if(!renderCanvas) {
			paper.text(xpos+paperWidth/2, ypos+paperHeight/2, text)
				.attr({ "font-size": fontSize,
					"font-family": fontFamily,
					"font-weight": fontWeight,
					fill: "90-#" + iconToColor + "-#" + iconFromColor, stroke: "none"
					}).rotate(theRotation);
		}
	}
	else {
		if(iconPathData != null) {
			var scaleFactor = icon.scaleFactor * iconmakerViewModel.symbolSize()/100;
			var translateX = icon.translateBy + iconmakerViewModel.symbolXPos();
			var translateY = icon.translateBy + iconmakerViewModel.symbolYPos();
			iconOnly = paper.path(iconPathData)
			.attr({fill: "90-#" + iconToColor + "-#" + iconFromColor, stroke: "none"})
				.translate(translateX, translateY)
				.scale(scaleFactor, scaleFactor)
				.rotate(iconmakerViewModel.symbolRotation());
		}
	}

	if (iconOnly != null && iconmakerViewModel.glow()) {
		iconOnly.glow({
				width: iconmakerViewModel.glowWidth(),
				fill: true,
				opacity: iconmakerViewModel.glowOpacity()/100,
				offsetx: iconmakerViewModel.glowOffsetX(),
				offsety: iconmakerViewModel.glowOffsetY(),
				color: '#' + iconmakerViewModel.glowColor()
		});
	}

	if(iconmakerViewModel.shiny()) {
		paper.path(icon.reflectionPath)
			.attr({"fill-opacity": icon.reflectionOpacity,
			fill: "#fff", stroke: "none"})
			.translate(icon.reflectionTranslateBy,icon.reflectionTranslateBy);
	}

	iconmakerViewModel.svgSupported(Raphael.type == "SVG");

	var svg = document.getElementById("icon").innerHTML;
	svg = svg.replace('<div id="svggroup">','').replace('</div>','');
	document.getElementById("stdin").value = svg;

	if(renderCanvas) {
		var newPaperSize = size || iconmakerViewModel.iconsize();
		paper.changeSize(newPaperSize, newPaperSize, false, false);
		svg = document.getElementById("icon").innerHTML;
		svg = svg.replace('<div id="svggroup">','').replace('</div>','');
		document.getElementById("stdin").value = svg;
		render(svg);

		if(!createFromShape) {
			var splitText = text.split("\n");
			var noOfRows = splitText.length;
			var c = document.getElementById('canvas');
			var context = c.getContext('2d');
			context.translate((xpos*(newPaperSize/138))+newPaperSize/2,fontSize/20 + ypos*(newPaperSize/138)+newPaperSize/2);
			var lingrad = context.createLinearGradient(0,-(fontSize * (newPaperSize/138))/2,0,fontSize / 2 * (newPaperSize/138));
			lingrad.addColorStop(0, '#' + iconFromColor);
			lingrad.addColorStop(1, '#' + iconToColor);
			context.fillStyle = lingrad;
			context.rotate(theRotation / (180/Math.PI))
			context.font = fontWeight + " " + fontSize * (newPaperSize/138) + "px " + fontFamily;
			context.textAlign = "center";
			context.textBaseline = "middle";

			if (false && iconmakerViewModel.glow()) {

				context.shadowColor = '#' + iconmakerViewModel.glowColor();
				context.shadowOffsetX = iconmakerViewModel.glowOffsetX();
				context.shadowOffsetY = iconmakerViewModel.glowOffsetY();
				context.shadowBlur = iconmakerViewModel.glowWidth();
			}

			var lineHeight = fontSize * (newPaperSize/138)*1.2;
			var totalHeight = lineHeight * noOfRows;
			for(var i=0;i<noOfRows;i++) {
				context.fillText(splitText[i], 0, -(totalHeight/2)+(lineHeight/2)+lineHeight*i);
			}
		}
	}
}

function setupIcons(){
	// raphael icons
	var clickRectangles = createIcons();

	var roundedSquareIcon = {
		name: "roundedSquare",
		image: "roundedsquare-shape.png",
		iconBackFunction: function(paperWidth, paperHeight){
			return paper.rect(0, 0, paperWidth, paperHeight, 17);
		},
		translateBy: 53,
		scaleFactor: 3.8,
		reflectionPath: "m 22.444235,4.4963017 c -9.75114,0 -17.6346801,8.7523503 -17.6346801,19.6274903 l 0,61.0011 c 9.6095001,1.77284 19.7746601,2.75828 30.2872401,2.75828 59.725779,0 108.138005,-30.66952 108.138005,-68.51632 0,-0.32871 -0.0285,-0.67181 -0.0357,-0.99938 -0.006,-0.0253 0.006,-0.0553 0,-0.08 -2.22194,-8.00058 -8.88753,-13.7912303 -16.81033,-13.7912303 l -103.944395,0 z",
		reflectionOpacity: 0.3,
		reflectionTranslateBy: -5
	};
	var sphereIcon = {
		name: "sphere",
		image: "sphere-shape.png",
		iconBackFunction: function(paperWidth, paperHeight){
			return paper.circle(paperWidth/2, paperHeight/2, paperWidth/2);
		},
		translateBy: 53,
		scaleFactor: 3.2,
		reflectionPath: "m 69.014556,-0.00369946 c -29.762634,0 -55.133675,18.78230946 -64.7415235,45.06249946 4.03e-5,0.0103 -9.05e-5,0.021 0,0.0312 0.1026507,11.59059 29.0196565,21 64.7100805,21 35.690407,0 64.638867,-9.40941 64.741507,-21 -0.003,-0.009 0.003,-0.0222 0,-0.0312 C 124.11665,18.77891 98.776981,-0.00369946 69.014556,-0.00369946 z",
		reflectionOpacity: 0.3,
		reflectionTranslateBy: 0
	};
	var squareIcon = {
		name: "square",
		image: "square-shape.png",
		iconBackFunction: function(paperWidth, paperHeight){
			return paper.rect(0, 0, paperWidth, paperHeight, 0);
		},
		translateBy: 53,
		scaleFactor: 3.8,
		reflectionPath: "m 5.5357143,5.6785711 0,93.1249999 L 131.30405,5.6857998 z",
		reflectionOpacity: 0.3,
		reflectionTranslateBy: 0
	};
	var noneIcon = {
		name: "none",
		image: "none-shape.png",
		iconBackFunction: null,
		translateBy: 53,
		scaleFactor: 3.8,
		reflectionPath: null,
		reflectionOpacity: 0.3,
		reflectionTranslateBy: 0
	};

	icons = [roundedSquareIcon, sphereIcon, squareIcon, noneIcon];

	createIcon(null);

	return clickRectangles;
}

function render(svg)
{
	var match = svg.match(/xmlns/g);
	if(match && match.length > 1) {
		svg = svg.replace('xmlns="http://www.w3.org/2000/svg"', '');
	}

	var c = document.getElementById('canvas');
	canvg(c, svg);
}

function downloadPng() {
	createIcon(cachedIconPathData,true);
	var canvas = document.getElementById("canvas");
	document.getElementById("svg").value = canvas.toDataURL("image/png");
	createIcon(cachedIconPathData,false);
	document.getElementById("svgform").submit();
}
