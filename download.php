<?php
ini_set('display_errors', '1');
include_once("include/webdesign.php");
include_once("include/hireshandler.php");
PrintStartHtml('Download',2,'Fix image orientation with the JPEG Rotator! Download high resolution images for printing! Play Project Cataclysm for some good old shoot em up action!');

?>

<div class="page-section">

<h2>Downloads</h2>

<!--
<p>
Fix image orientation with the <a href="#jpegrotator">JPEG Rotator</a>! Download <a href="#hires">high resolution images</a> for printing! Play <a href="#projectc">Project Cataclysm</a> for some good old shoot em up action!
</p>
-->

</div>

<div class="page-section">
  <h2 id="tentinylevels">Ten Tiny Levels for Android</h2>

  <p>
    Fast-paced platform arcade action for one or two players! Check out the <a href="/ten-tiny-levels">microsite</a> or download using the badge below.
  </p>

  <div class="flex-container">
  <?php
  $images = [
    0 => 'screenshot-01.png',
    1 => 'screenshot-02.png'
  ];

  $descriptions = [
    0 => 'The game has both single and local multiplayer modes.',
    1 => "Eliminate all enemies and don't let the timer run out!"
  ];

  foreach ($images as $i => $image) {
      ?>
        <div class="thumb_small" style="float: none; text-align: center;">
          <a href="/ttl-assets/<?php echo $image ?>" class="gallery-thumbnail">
          <img style="max-height: 280px; width: auto;" src="/ttl-assets/<?php echo $image ?>" alt="<?php echo $descriptions[$i] ?>" title="<?php echo $descriptions[$i] ?>" />
        </a>
        <div class="imagedescription"><?php echo $descriptions[$i] ?></div>
        </div>
      <?php
  }
  ?>
  </div>

  <div style="text-align: center; margin-top: -5px;">
    <a href="https://play.google.com/store/apps/details?id=com.regalraccoongames.tentinylevels">
      <img class="google-play-badge" style="max-width: 250px" src="/ttl-assets/google-play-badge.png">
    </a>
  </div>
</div>

<!--
<div class="page-section">

<h3 id="jpegrotator">JPEG Rotator</h3>

<p>
When shooting vertically, some cameras rotate images using EXIF metadata, but they still later show up in Windows in landscape format. JPEG Rotator to the rescue! This small application automatically corrects orientation based on the EXIF metadata.
</p>
<div style="text-align: center;">
<a href="/jpegrotator">
<img src="/content/images/jpegrotator.jpg" alt="JPEG Rotator" style="max-width: 100%;" />
</a>
</div>
<p>
<b><a href="/jpegrotator">Read more</a> or <a href="/download/jpegrotator.zip">Get it now!</a></b>
</p>

</div>
-->

<div class="page-section">

<h2 id="projectc">Project Cataclysm</h2>

<p>
A game I developed in back in 2002, called "Project Cataclysm". You can think of it like Bubble Bobble, except with machineguns (shotguns and grenades are also available). It can be played in single player mode, or in multiplayer with two players on the same computer, in deathmatch or cooperative mode. A precursor to Ten Tiny Levels if you will.
</p>
<p>
It's a bit old by now and won't run under modern versions of Windows without some tricks - the easiest way, however, is to install <a href="http://www.dosbox.com/" target="_blank">DosBox</a>.
</p>
<p>
Download and play for free. A must if you like good ol' DOS games!
</p>
<p>
<b><a href="/download/project_c.zip">Get it now! (450KB)</a></b>
</p>

<div class="flex-container wrap">
<?php
$markup = '';

$desc2 = "Mind-blowing outdoor levels";
$markup .= '<div class="thumb_small" style="float: none; text-align: center;">';
$markup .= "<a href=\"/content/images/projectc2.jpg\" class=\"gallery-thumbnail\">";
$markup .= "<img src=\"/content/images/projectc2_thumb.jpg\" style=\"width: auto;\" alt=\"" . $desc2 . "\" title=\"" . $desc2 . "\" />";
$markup .= "</a>";
$markup .= '<div class="imagedescription" style="max-width: 200px;">' . $desc2 . '</div>';
$markup .= '</div>';

$desc1 = "Cooperative multiplayer action";
$markup .= '<div class="thumb_small" style="float: none; text-align: center;">';
$markup .= "<a href=\"/content/images/projectc1.jpg\" class=\"gallery-thumbnail\">";
$markup .= "<img src=\"/content/images/projectc1_thumb.jpg\" style=\"width: auto;\" alt=\"" . $desc1 . "\" title=\"" . $desc1 . "\" />";
$markup .= "</a>";
$markup .= '<div class="imagedescription" style="max-width: 200px;">' . $desc1 . '</div>';
$markup .= '</div>';

echo $markup;
?>
</div>

</div>

<div class="page-section">

<h2 id="hires">Background Images</h2>

<p>Images provided in full resolution. Feel free to download and use them as you wish - desktop background, or maybe for printing? Click on each of them to download.</p>

<div class="flex-container wrap">
<?php

DisplayHiresImages();

?>
</div>

<div class="clearfix"></div>

</div>
</div>

<?php
PrintEndHtml();
?>
