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
Thanks to <a href="http://raphaeljs.com/icons/" target="_blank">Raphaël JS</a> for the original symbols, and <a href="http://jscolor.com/" target="_blank">JSColor</a> for the color picker.
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
</form>

<form id="pngform" action="downloadFileIcon.php" method="post">
	<input name="stdin" id="stdin" type="hidden" />
	<input type="hidden" name="pngsize" id="pngsize" />
</form>

<div id="iconmaker">

<div class="page-section">

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
<div class="page-section">

<h3>Background color</h3>
<label>From:</label>
<input id="iconBackFromColor" class="color textinput" size="6" data-bind="value: iconBackFromColor" />
<label>To:</label>
<input id="iconBackToColor" class="color textinput" size="6" data-bind="value: iconBackToColor" />

</div>
<div class="page-section">

<h3>Icon Color</h3>
<label>From:</label>
<input id="iconFromColor" class="color textinput" size="6" data-bind="value: iconFromColor" />
<label>To:</label>
<input id="iconToColor" class="color textinput" size="6" data-bind="value: iconToColor" />

<div style="clear: both;"></div>

</div>
<div class="page-section">

<h3>Symbol Type</h3>
<input type="radio" name="iconType" value="icon" data-bind="checked: iconType" id="iconTypeIcon" class="checkbox" /><label for="iconTypeIcon" class="checkbox-label" data-bind="css: { unchecked: iconType() != 'icon' }">Use Predefined Symbols</label>
<br />
<input type="radio" name="iconType" value="text" data-bind="checked: iconType" id="iconTypeText" class="checkbox" /><label for="iconTypeText" class="checkbox-label" data-bind="css: { unchecked: iconType() != 'text' }">Use Text Input</label>

</div>
<div data-bind="fadeVisible: iconType() == 'icon'">

<div class="page-section">

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
<div class="page-section">
	
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
	<h3>Icon Preview</h3>
	<a href="javascript:void(0);" data-bind="click: randomize" id="randomize-icon">Surprise me!</a>
	<div id="icon"></div>
	<div id="downloadTip">Tip: Adjust the settings and then download the icon using the button at the end of the page.</div>
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
<input name="fileType" id="fileTypePng" type="radio" value="png" data-bind="checked: fileType" class="checkbox" /><label for="fileTypePng" class="checkbox-label" data-bind="css: { unchecked: fileType() != 'png' }">PNG (Rasterized image)</label>
<label for="iconsize"> - Image Size: </label>
<input type="text" class="textinput" maxlength="3" size="3" data-bind="value: iconsize, enable: fileType() == 'png'" />
<label> px (width and height, max 256)</label>
<br />
<input name="fileType" id="fileTypeSvg" type="radio" value="svg" data-bind="checked: fileType" class="checkbox" /><label for="fileTypeSvg" class="checkbox-label" data-bind="css: { unchecked: fileType() != 'svg' }">SVG (Vector image)</label>
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
		ko.applyBindings(iconmakerViewModel);
		paper = ScaleRaphael("icon", 138, 138)
		
		var top = 200;
		$('#iconContainer').css('top', top + 'px');
		$(window).scroll(function() {
			var scrollTop = $(this).scrollTop() + 10;
			$('#iconContainer').css('top', (scrollTop > top ? scrollTop : top) + 'px');
		});
		
		var clickableIcons = setupIcons();
		iconmakerViewModel.clickableIcons = clickableIcons;
		
		// create a random icon
		iconmakerViewModel.randomize();
		
		$('#loader').hide();
		$('#iconmaker').fadeIn('fast');
		$("input:text").focus(function() { $(this).select(); } );
		
		$(document).on({
			mouseenter: function() {
				$(this).stop().fadeTo('fast', 0.6);
			},
			mouseleave: function() {
				$(this).stop().fadeTo('fast', 1);
			}
		}, '#saved-presets li img');
		
		// social plugins are super slow sometimes so defer loading
		$('#plusone-container').html('<g:plusone></g:plusone>');
		$('#twitter-container').html('<a style="margin-left: 10px;" href="http://twitter.com/share" class="twitter-share-button" data-text="Shiny Icon Maker - Make your own Web 2.0 Icons!" data-count="horizontal" data-via="erikmoberg_swe">Tweet</a><scr' + 'ipt type="text/javascript" src="http://platform.twitter.com/widgets.js"></scr' + 'ipt>');
		$('#facebook-container').html('<scr' + 'ipt src="http://connect.facebook.net/en_US/all.js#xfbml=1"></scr' + 'ipt><fb:like href="http://www.erikmoberg.net/iconmaker" layout="button_count" show_faces="true" width="450" font=""></fb:like>');
	});
	
</script>

<?php
PrintEndHtml();
?>