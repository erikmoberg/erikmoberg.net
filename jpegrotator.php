<?php
include_once("include/webdesign.php");
include_once("include/imagehandler.php");
PrintStartHtml('Download - JPEG Rotator',3,'Download - JPEG Rotator');

?>

<div class="page-section">

<h2>JPEG Rotator</h2>

<h3>The Background</h3>
<p>
Many cameras, like the Canon 1000D, automatically detects the orientation of the camera when taking a picture, and can rotate images accordingly. This will make vertical shots to actually appear in portrait orientation.
</p>

<h3>The Problem</h3>
<p>
The problem is, the rotation is made using so called EXIF metadata - instead of actually rotating the image, the files contain information about its rotation. When opening an image, it should be rotated if needed. Some image viewers, perhaps most notably the built-in image viewers in Windows XP/Vista/7, do not read this data. A vertical shot image will therefore appear in landscape mode and will require manual correction.
</p>

<div class="thumb_small" style="float: none; text-align: center;">
<a href="content/images/wrongorientation.jpg" class="gallery-thumbnail">
<img src="content/images/wrongorientation_thumb.jpg" alt="Wrong JPEG orientation - this ever happened to you?" title="Wrong JPEG orientation - this ever happened to you?" />
</a>
<div class="imagedescription">This ever happened to you?</div>
</div>

<h3>The Solution</h3>
<p>
I needed a small application to batch-rotate the images, so I rolled my own. Just select a folder and the images that need to be rotated will be rotated for you. And no installation needed!
</p>

<div style="text-align: center;">
<a href="download/jpegrotator.zip">
<img src="content/images/jpegrotator.jpg" alt="JPEG Rotator" style="max-width: 100%;" />
</a>
</div>

<h3>How to use it</h3>
<ol>
<li>Click the Open Folder button.</li>
<li>Select the folder containing the images.</li>
<li>A confirmation box shows. Click Ok to start processing.</li>
<li>Wait (or click Abort to cancel the operation).</li>
<li>All done!</li>
</ol>

<p>If you have comments or find any bugs, just <a href="contact.php">contact me.</a></p>

<div>
<b><a href="download/jpegrotator.zip">Get it now! (Version 1.0, 20 KB)</a></b>
</div>

<div class="clearfix"></div>
</div>

<div class="page-section">

<h3>Things to note</h3>

<p>This software...</p>

<ul>
<li>comes with zero warranty. Try the software on a few pictures before letting it go through your complete collection, and always keep a backup of your pictures.</li>
<li>requires .NET Framework 3.5 Client Profile or newer. If you are running Windows 7 you got that covered already - otherwise get a small installer <a target="_blank" href="http://www.hanselman.com/smallestdotnet/">here.</a></li>
<li>rotates JPEG (*.jpg) images only, using a lossless rotation algorithm (at least it should be, according to the docs). A lossless rotation, however, is only possible if the image dimensions are exact multiples of the Minimum Coded Unit - typically 8x8 or 16x16 pixels. Otherwise, a lossy transform is performed. Luckily, virtually all digital cameras use a resolution that plays nice with the MCU.</li>
</ul>

</div>

<?php
PrintEndHtml();
?>