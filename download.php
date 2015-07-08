<?php
ini_set('display_errors', '1');
include_once("include/webdesign.php");
include_once("include/hireshandler.php");
PrintStartHtml('Download',2,'Fix image orientation with the JPEG Rotator! Download high resolution images for printing! Play Project Cataclysm for some good old shoot em up action!');

?>

<div class="page-section">

<h2>Downloads</h2>

<p>
Fix image orientation with the <a href="#jpegrotator">JPEG Rotator</a>! Download <a href="#hires">high resolution images</a> for printing! Play <a href="#projectc">Project Cataclysm</a> for some good old shoot em up action!
</p>

<p>
If you like anything you find here, please find it in your heart to use the button below to buy me a burger. Double cheese please! ;)
</p>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="6PL45QBHGVR2C">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>

</div>
<div class="page-section">

<h3 id="jpegrotator">JPEG Rotator</h3>

<p>
When shooting vertically, some cameras rotate images using EXIF metadata, but they still later show up in Windows in landscape format. JPEG Rotator to the rescue! This small application automatically corrects orientation based on the EXIF metadata.
</p>
<div style="text-align: center;">
<a href="/jpegrotator" style="background: none;">
<img src="/content/images/jpegrotator.jpg" alt="JPEG Rotator" />
</a>
</div>
<p>
<b><a href="/jpegrotator">Read more</a> or <a href="/download/jpegrotator.zip">Get it now!</a></b>
</p>

</div>
<div class="page-section">

<h3 id="hires">High Resolution Images</h3>

<p>These are a few of photos that I am particularly satisfied with. Feel free to download and use them as you wish. All in pictures are provided in full resolution. Click on each of them to download.</p>

<?php

DisplayHiresImages();

?>

<div class="clearfix"></div>

</div>
<div class="page-section">

<h3 id="projectc">Project Cataclysm</h3>

<p>
A game I developed in back in 2002, called "Project Cataclysm". You can think of it like Bubble Bobble, except with machineguns (shotguns and grenades are also available). It can be played in single player mode, or in multiplayer with two players on the same computer, in deathmatch or cooperative mode.
</p>
<p>
It's a bit old by now and won't run under Windows 7 without some tricks - the easiest way, however, is to install <a href="http://www.dosbox.com/" target="_blank">DosBox</a>.
</p>
<p>
Download and play for free. A must if you like good ol' DOS games!
</p>
<p>
<b><a href="/download/project_c.zip">Get it now! (450KB)</a></b>
</p>

<?php
$markup = '';

$desc2 = "Mind-blowing outdoor levels";
$markup .= '<div class="thumb_small" style="float: none; text-align: center;">';
$markup .= "<a href=\"/content/images/projectc2.jpg\" class=\"gallery-thumbnail\">";
$markup .= "<img src=\"/content/images/projectc2_thumb.jpg\" alt=\"" . $desc2 . "\" title=\"" . $desc2 . "\" />";
$markup .= "</a>";
$markup .= '<div class="imagedescription">' . $desc2 . '</div>';
$markup .= '</div>';

$desc1 = "Cooperative multiplayer action";
$markup .= '<div class="thumb_small" style="float: none; text-align: center;">';
$markup .= "<a href=\"/content/images/projectc1.jpg\" class=\"gallery-thumbnail\">";
$markup .= "<img src=\"/content/images/projectc1_thumb.jpg\" alt=\"" . $desc1 . "\" title=\"" . $desc1 . "\" />";
$markup .= "</a>";
$markup .= '<div class="imagedescription">' . $desc1 . '</div>';
$markup .= '</div>';

echo $markup;
?>

</div>
</div>

<?php
PrintEndHtml();
?>