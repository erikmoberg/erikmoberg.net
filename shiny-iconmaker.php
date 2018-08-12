<?php
//ini_set('display_errors', '1');
include_once("include/webdesign.php");
PrintStartHtml('Shiny Icon Maker',3,'The Shiny Icon Maker is a simple tool to create your own free custom Web 2.0-style icons for your applications or websites using text, predefined icons, and the colors of your choice.');

?>
<div class="page-section">
<h2>Shiny Icon Maker</h2>
<p>
Use the Shiny Icon Maker to create your own Web 2.0-style icons for your applications or websites - using text or predefined icons, and customizing the colors. All free!
</p>
<p>
To download the icon as a PNG or SVG file, click the "Download Icon" button at the bottom of the page. You can also use the "Save for later" function and save up to 5 settings.
</p>
<p>
	<h4>Icon Sets License Information</h4>
	<p>
		I have not created these icons myself and they use different licenses.
		By using any icons, you confirm that you have reviewed the license terms for the corresponding icon set.
		Hint: When downloading an icon, the set will be a part of the file name for your convenience.
	</p>
	<ul>
		<li><a target="_blank" href="http://www.toicon.com/series/afiado">Afiado</a> - <a target="_blank" href="http://www.toicon.com/license">CC BY 4.0 License</a></li>
		<li><a target="_blank" href="http://www.entypo.com">Entypo+</a> - <a target="_blank" href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0 License</li>
		<li><a target="_blank" href="https://github.com/colebemis/feather">Feather</a> - <a target="_blank" href="https://opensource.org/licenses/MIT">MIT License</a></li>
		<li><a target="_blank" href="https://icomoon.io/#icons-icomoon">IcoMoon - Free - <a target="_blank" href="https://icomoon.io/#icons-icomoon">GPL or CC BY 4.0</a></li>
		<li><a target="_blank" href="http://dmitrybaranovskiy.github.io/raphael/">Raphael</a> - <a target="_blank" href="https://opensource.org/licenses/MIT">MIT License</a></li>
	</ul>
</p>

</div>
<div class="page-section">

	<div>
		<div class="social-link" id="plusone-container"></div>
		<div class="social-link" id="twitter-container"></div>
		<div class="social-link" id="facebook-container"></div>
	</div>
	<div class="clearfix"></div>

</div>

<form id="svgform" action="downloadFile.php" method="post">
	<input name="svg" id="svg" type="hidden" />
	<input name="downloadIconName" id="downloadIconName" type="hidden" />
</form>

<form id="pngform" action="downloadFileIcon.php" method="post">
	<input name="stdin" id="stdin" type="hidden" />
	<input type="hidden" name="pngsize" id="pngsize" />
</form>

<div id="iconmaker">

	<div class="page-section">
		<h3>Mode</h3>
		<input type="radio" name="mode" value="simple" data-bind="checked: mode" id="iconTypeSimple" class="checkbox" /><label for="iconTypeSimple" class="checkbox-label" data-bind="css: { unchecked: mode() != 'simple' }">Simple (flat)</label>
		<br />
		<input type="radio" name="mode" value="advanced" data-bind="checked: mode" id="iconTypeAdvanced" class="checkbox" /><label for="iconTypeAdvanced" class="checkbox-label" data-bind="css: { unchecked: mode() != 'advanced' }">Advanced</label>
		<div class="clearfix"></div>
	</div>

<div class="page-section" data-bind="visible: mode() === 'advanced'">

<h3>Background Shape</h3>
<div id="shapes">
<input data-bind="checked: shapeType" checked="checked" value="roundedSquare" id="roundedSquareshape" name="shapeType" type="radio" />
<label for="roundedSquareshape"><img class="background-shape" alt="roundedSquareshape" src="content/images/roundedsquare-shape.png" /></label>

<input data-bind="checked: shapeType" value="sphere" id="sphereshape" name="shapeType" type="radio" />
<label for="sphereshape"><img class="background-shape" alt="sphereshape" src="content/images/sphere-shape.png" /></label>

<input data-bind="checked: shapeType" value="square" id="squareshape" name="shapeType" type="radio" />
<label for="squareshape"><img class="background-shape" alt="squareshape" src="content/images/square-shape.png" /></label>

<input data-bind="checked: shapeType" value="none" id="noneshape" name="shapeType" type="radio" />
<label for="noneshape"><img class="background-shape" alt="noneshape" src="content/images/none-shape.png" /></label>
</div>
<br />
<input type="checkbox" class="checkbox" id="shiny" data-bind="checked: shiny" /><label for="shiny" class="checkbox-label" data-bind="css: { unchecked: !shiny() }">Add shiny effect</label>
<div class="clearfix"></div>

<div class="clearfix"></div>

</div>
<div class="page-section" data-bind="visible: mode() === 'advanced'">

<h3>Background color</h3>
<label>From:</label>
<input id="iconBackFromColor" class="color textinput" size="6" data-bind="value: iconBackFromColor" />
<label>To:</label>
<input id="iconBackToColor" class="color textinput" size="6" data-bind="value: iconBackToColor" />

</div>

<div class="page-section" data-bind="visible: mode() === 'advanced'">
	<h3>Icon Color</h3>
	<label>From:</label>
	<input id="iconFromColor" class="color textinput" size="6" data-bind="value: iconFromColor" />
	<label>To:</label>
	<input id="iconToColor" class="color textinput" size="6" data-bind="value: iconToColor" />
	<div style="clear: both;"></div>
</div>

<div class="page-section" data-bind="visible: mode() === 'simple'">
	<h3>Icon Color</h3>
	<input id="iconFromAndToColor" class="color textinput" size="6" data-bind="value: iconFromColor" />
	<div style="clear: both;"></div>
</div>

<div class="page-section" data-bind="visible: mode() === 'advanced'">

<h3>Symbol Type</h3>
<input type="radio" name="iconType" value="icon" data-bind="checked: iconType" id="iconTypeIcon" class="checkbox" /><label for="iconTypeIcon" class="checkbox-label" data-bind="css: { unchecked: iconType() != 'icon' }">Use Predefined Symbols</label>
<br />
<input type="radio" name="iconType" value="text" data-bind="checked: iconType" id="iconTypeText" class="checkbox" /><label for="iconTypeText" class="checkbox-label" data-bind="css: { unchecked: iconType() != 'text' }">Use Text Input</label>

</div>
<div data-bind="fadeVisible: iconType() == 'icon'">

<div class="page-section" data-bind="visible: mode() === 'advanced'">

<h3>Symbol Options</h3>

	<div class="slider-container">
		<label class="slider-label">Symbol size</label>
		<input type="text" class="slider-value textinput" data-bind="value: symbolSize, valueUpdate: 'afterkeydown'" />
		<span class="slider-unit">%</span>
		<div id="symbolSizeSlider" data-bind="jqSlider: symbolSize, jqOptions: { min: 1, max: 300 }"></div>
	</div>

	<div class="slider-container">
		<label class="slider-label">Rotation</label>
		<input type="text" class="slider-value textinput" id="symbolRotation" data-bind="value: symbolRotation, valueUpdate: 'afterkeydown'" />
		<span class="slider-unit">°</span>
		<div id="symbolRotationSlider" data-bind="jqSlider: symbolRotation, jqOptions: { min: 0, max: 360 }"></div>
	</div>

	<div class="slider-container">
		<label class="slider-label">Horizontal position</label>
		<input type="text" class="slider-value textinput" data-bind="value: symbolXPos, valueUpdate: 'afterkeydown'" />
		<span class="slider-unit">px</span>
		<div id="symbolXPosSlider" data-bind="jqSlider: symbolXPos, jqOptions: { min: -100, max: 100 }"></div>
	</div>

	<div class="slider-container">
		<label class="slider-label">Vertical position</label>
		<input type="text" class="slider-value textinput" data-bind="value: symbolYPos, valueUpdate: 'afterkeydown'" />
		<span class="slider-unit">px</span>
		<div id="symbolYPosSlider" data-bind="jqSlider: symbolYPos, jqOptions: { min: -100, max: 100 }"></div>
	</div>

</div>
<div class="page-section" data-bind="visible: mode() === 'advanced'">

	<h3>Glow/Drop Shadow Options</h3>

	<input type="checkbox" class="checkbox" id="glow" data-bind="checked: glow" /><label for="glow" class="checkbox-label" data-bind="css: { unchecked: !glow() }">Add glow/drop shadow effect</label>

	<div data-bind="fadeVisible: glow">
		<div class="color-container">
			<label class="slider-label">Color</label>
			<input id="glowColor" class="color textinput color-value" size="6" data-bind="value: glowColor, valueUpdate: 'afterkeydown'" />
		</div>

		<div class="slider-container">
			<label class="slider-label">Opacity</label>
			<input type="text" class="slider-value textinput" data-bind="value: glowOpacity, valueUpdate: 'afterkeydown'" />
			<span class="slider-unit">%</span>
			<div id="glowOpacitySlider" data-bind="jqSlider: glowOpacity, jqOptions: { min: 0, max: 100 }"></div>
		</div>

		<div class="slider-container">
			<label class="slider-label">Width</label>
			<input type="text" class="slider-value textinput" data-bind="value: glowWidth, valueUpdate: 'afterkeydown'" />
			<span class="slider-unit">px</span>
			<div id="glowWidthSlider" data-bind="jqSlider: glowWidth, jqOptions: { min: 0, max: 50 }"></div>
		</div>

		<div class="slider-container">
			<label class="slider-label">Horizontal offset</label>
			<input type="text" class="slider-value textinput" data-bind="value: glowOffsetX, valueUpdate: 'afterkeydown'" />
			<span class="slider-unit">px</span>
			<div id="glowOffsetXSlider" data-bind="jqSlider: glowOffsetX, jqOptions: { min: -30, max: 30 }"></div>
		</div>

		<div class="slider-container">
			<label class="slider-label">Vertical offset</label>
			<input type="text" class="slider-value textinput" data-bind="value: glowOffsetY, valueUpdate: 'afterkeydown'" />
			<span class="slider-unit">px</span>
			<div id="glowOffsetYSlider" data-bind="jqSlider: glowOffsetY, jqOptions: { min: -30, max: 30 }"></div>
		</div>

	</div>

	</div>
	<div class="page-section">

	<h3>Symbol Selection</h3>

	<input type="text" class="textinput" data-bind="textInput: symbolName" placeholder="Search by name" style="width:280px" />
  <a href="javascript:void(0);" data-bind="click: function() { randomize(); }" id="randomize-icon">Surprise me!</a>
	<div class="symbol-set-selection">
		<h4>Included Icon Sets</h4>
		<div>
			<input type="radio" class="checkbox" data-bind="value: null, checked: $root.selectedSymbolSet" id="allSymbolSets" />
			<label class="checkbox-label" data-bind="css: { unchecked: $root.selectedSymbolSet() !== null }" for="allSymbolSets">All</label>
			<!-- ko foreach: symbolSets -->
			<input type="radio" class="checkbox" data-bind="value: id, checked: $root.selectedSymbolSet, attr: {id: id}" />
			<label class="checkbox-label" data-bind="text: name, css: { unchecked: id !== $root.selectedSymbolSet() }, attr: {for: id}"></label>
			<!-- /ko -->
		</div>
	</div>

	<div id="symbols"></div>
	</div>
</div>

<div data-bind="fadeVisible: iconType() == 'text'" class="page-section">

<h3>Text Options</h3>

	<label>Text</label>
	<br />
	<textarea id="iconText" data-bind="value: iconText, valueUpdate: 'afterkeydown'" class="textinput">Sample</textarea>
	<br />
	<br />
	<label>Font</label>
	<br />
	<select id="iconFontFamily" class="textinput" data-bind="options: fonts, value: iconFontFamily"></select>
	<input type="checkbox" id="iconFontWeight" data-bind="checked: bold" class="checkbox" />
	<label for="iconFontWeight" class="checkbox-label" data-bind="css: { unchecked: !bold() }">Bold</label>
	<br />
	<br />

	<div class="slider-container">
		<label class="slider-label">Font size</label>
		<input type="text" class="slider-value textinput" id="fontSizeText" data-bind="value: fontSize, valueUpdate: 'afterkeydown'" />
		<div id="fontSizeSlider" data-bind="jqSlider: fontSize, jqOptions: { min: 8, max: 300 }"></div>
		<span class="slider-unit">pt</span>
	</div>

	<div class="slider-container">
		<label class="slider-label">Rotation</label>
		<input type="text" class="slider-value textinput" id="rotationText" data-bind="value: textRotation, valueUpdate: 'afterkeydown'" />
		<div id="rotationSlider" data-bind="jqSlider: textRotation, jqOptions: { min: 0, max: 360 }"></div>
		<span class="slider-unit">°</span>
	</div>

	<div class="slider-container">
		<label class="slider-label">Horizontal Position</label>
		<input type="text" class="slider-value textinput" id="xposText" data-bind="value: textXPos, valueUpdate: 'afterkeydown'" />
		<div id="xposSlider" data-bind="jqSlider: textXPos, jqOptions: { min: -100, max: 100 }"></div>
		<span class="slider-unit">px</span>
	</div>

	<div class="slider-container">
		<label class="slider-label">Vertical Position</label>
		<input type="text" class="slider-value textinput" id="yposText" data-bind="value: textYPos, valueUpdate: 'afterkeydown'" />
		<div id="yposSlider" data-bind="jqSlider: textYPos, jqOptions: { min: -100, max: 100 }"></div>
		<span class="slider-unit">px</span>
	</div>
</div>


<div id="iconContainer">
	<div class="displayIconName">
		<span data-bind="text: displayIconName"></span>
	</div>
	<div id="icon"></div>
	<div id="downloadTip"><a href="javascript:void(0)" data-bind="click: function() { emnet.utils.scrollIntoView('btnSave'); }">Go to download</a></div>
</div>

<div style="clear: both;"></div>

<div class="page-section">
<h3>Save for later</h3>
<p>You can save up to 5 settings here and load them later (also on your next visits).</p>

<ul id="saved-presets">
	<!-- ko foreach: savedPresets -->
	<li data-bind="click: function() { $root.setSettings($data); }">
		<img data-bind="attr: {src: imageData}" />
		<a href="javascript:void(0)" class="delete-preset" data-bind="click: function() { $root.deletePreset($data); }"></a>
	</li>
	<!-- /ko -->
	<li id="add-setting" data-bind="click: addPreset, visible: savedPresets().length < 5">
		Click to add
	</li>
</ul>

<p class="info-text" data-bind="visible: savedPresets().length >= 5"><b>You have used all 5 slots.</b> To save more settings, you must remove an old one.</p>
</div>

<div class="page-section">
<h3>Download icon</h3>
<input name="fileType" id="fileTypePng" type="radio" value="png" data-bind="checked: fileType" class="checkbox" /><label for="fileTypePng" class="checkbox-label" data-bind="css: { unchecked: fileType() != 'png' }">PNG</label>
<label for="iconsize"> - Image Size: </label>
<input type="text" class="textinput" maxlength="4" data-bind="value: iconsize, enable: fileType() == 'png'" style="width: 40px;" />
<label> px (max 1024)</label>
<br />
<input name="fileType" id="fileTypeSvg" type="radio" value="svg" data-bind="checked: fileType" class="checkbox" /><label for="fileTypeSvg" class="checkbox-label" data-bind="css: { unchecked: fileType() != 'svg' }">SVG</label>
<br />&nbsp;
<p>
<label id="noSvg" data-bind="visible: !svgSupported()" style="display: none;"><b>Your browser does not support SVG.</b> Use an SVG compliant browser to download the icon.</label>
<button class="btn" id="btnSave" data-bind="visible: svgSupported" onclick="javascript:downloadIcon();">Download Icon</button>
</p>
</div>

<canvas id="canvas" width="100" height="100" style="visibility: hidden; position: absolute;">
</canvas>
</div>

<div id="loader">
	<div class="page-section">
		<h3>Loading the Shiny Icon Maker...</h3></div>
	</div>
</div>

<script type="text/javascript">

$(function() {

		$( "#shapes" ).buttonset();
		iconmakerViewModel = new IconmakerViewModel();

		paper = ScaleRaphael("icon", 138, 138)

		var top = 200;
		$('#iconContainer').css('top', top + 'px');
		$(window).scroll(function() {
			var scrollTop = $(this).scrollTop() + 10;
			$('#iconContainer').css('top', (scrollTop > top ? scrollTop : top) + 'px');
		});

		var clickableIcons = setupIcons();
		iconmakerViewModel.clickableIcons = clickableIcons;

		var fakeSymbolSets = [];
		$.each(clickableIcons, function(i, r) {
			if (fakeSymbolSets.indexOf(r.set) === -1) {
				fakeSymbolSets.push(r.set);
			}
		});

		fakeSymbolSets.sort();

		$.each(fakeSymbolSets, function(i, r) {
			iconmakerViewModel.symbolSets.push({name: r, id: "symbolset" + i, checked: ko.observable(true)});
		});

		// create a random icon
		iconmakerViewModel.randomize();
		iconmakerViewModel.mode('simple');

		$('#loader').hide();
		$('#iconmaker').fadeIn('fast');
		//$("input:text").focus(function() { $(this).select(); } );

		$(document).on({
			mouseenter: function() {
				$(this).stop().fadeTo('fast', 0.6);
			},
			mouseleave: function() {
				$(this).stop().fadeTo('fast', 1);
			}
		}, '#saved-presets li img');

		ko.applyBindings(iconmakerViewModel);

		// social plugins are super slow sometimes so defer loading
		$('#plusone-container').html('<g:plusone></g:plusone>');
		$('#twitter-container').html('<a style="margin-left: 10px;" href="https://twitter.com/share" class="twitter-share-button" data-text="Shiny Icon Maker - Make your own Web 2.0 Icons!" data-count="horizontal" data-via="erikmoberg_swe">Tweet</a><scr' + 'ipt type="text/javascript" src="https://platform.twitter.com/widgets.js"></scr' + 'ipt>');
		$('#facebook-container').html('<scr' + 'ipt src="https://connect.facebook.net/en_US/all.js#xfbml=1"></scr' + 'ipt><fb:like href="https://www.erikmoberg.net/iconmaker" layout="button_count" show_faces="true" width="450" font=""></fb:like>');
	});

</script>

<?php
PrintEndHtml();
?>
