<?php
ini_set('display_errors', '1');
require_once("include/webdesign.php");
PrintStartHtml('Images',1,'Image Gallery');
?>

<?php
$galleryid = '';
if(isset($_GET['galleryid']))
	$galleryid = $_GET['galleryid'];
	
if($galleryid != '' && GalleryExists($galleryid))
{
	DisplaySingleGallery($galleryid);
}
else
{
	DisplayMainGallery();
}
?>

<?php
PrintEndHtml();
?>
