<?php
function LoadImageXml()
{
	$xmlFile = 'xml/images-en.xml';
	if (file_exists($xmlFile))
	{
	    return simplexml_load_file($xmlFile); 
	}
	else
	{
	    exit('Failed to open $xmlFile.');
	}
}
function GalleryExists($galleryid)
{
	$xml = LoadImageXml();
	foreach($xml->gallery as $gallery)
	{
		if($gallery['id'] == $galleryid)
			return true;
	}
	return false;
}

function DisplayMainGallery()
{
	$xml = LoadImageXml();
	echo "<h2>All galleries</h2>";
	foreach ($xml->gallery as $gallery)
	{
		$galleryname = $gallery->name;
		$gallerydatetime = $gallery->datetime;
		$galleryfrontpic = 'images/' . $gallery->basedir . '/' . $gallery->frontpic;
		$galleryid = $gallery['id'];
		PrintMainGalleryImage($galleryname, $gallerydatetime, $galleryfrontpic, $galleryid);
	}
}
function PrintMainGalleryImage($galleryname, $gallerydatetime, $galleryfrontpic, $galleryid)
{
	$currentstyle = GetCurrentStyle();
	if($gallerydatetime != '')
		$galleryname = $gallerydatetime . ' - ' . $galleryname;
	echo '<div class="maingalleryitem">';
	echo "<a href=\"?galleryid=$galleryid&amp;style=$currentstyle\"><img src=\"$galleryfrontpic\" alt=\"$galleryname\" /></a>";
	echo '<br />';
	echo "<a href=\"?galleryid=$galleryid&amp;style=$currentstyle\">$galleryname</a>";
	echo '</div>';
}

function DisplaySingleGallery($galleryid)
{
	$xml = LoadImageXml();
	foreach($xml->gallery as $gallery)
	{
		if($gallery['id'] == $galleryid)
			$gallerynode = $gallery;
	}
	$galleryname = $gallerynode->name;
	$gallerdescription = $gallerynode->description;
	echo "<h2>Gallery: $galleryname</h2>";
	echo "<p>$gallerdescription</p>";
	$backlink = '<p><a href="' . CreateSimpleLink('images.php') . '">&lt;&lt; Back</a></p>';
	echo $backlink;
	
	echo '<div class="singlegalleryitem">';
	echo '<a id="lnkplaceholder" href=""><img id="placeholder" src="images/blank.gif" alt="" /></a>';
	echo '<br />';
	echo '<p id="desc">Choose an image to begin</p>';
	echo '</div>';
	
	echo '<div class="thumb_small">';
	$imagebasedir = 'images/' . $gallerynode->basedir;
	foreach($gallerynode->images->image as $image)
	{
		$imagedescription = str_replace('"',"\'\'",str_replace("'","\'",$image->description));
		$basefilename = $imagebasedir . '/' . $image->basefilename;
		$imagethumb = $basefilename . '_thumb.jpg';
		$imagethumb_small = $basefilename . '_thumb_small.jpg';
		$imagefile = $basefilename . '.jpg';
		
		echo "<a href=\"$imagethumb\" onclick=\"javascript:return showPic('$imagefile','$imagethumb','$imagedescription');\"><img src=\"$imagethumb_small\" alt=\"$imagedescription\" /></a>";
	}
	echo '</div>';
	
	//Display first image when gallery first is opened
	$imagedescription = str_replace('"',"\'\'",str_replace("'","\'",$gallerynode->images->image->description));
	$basefilename = $imagebasedir . '/' . $gallerynode->images->image->basefilename;
	$imagethumb = $basefilename . '_thumb.jpg';
	$imagefile = $basefilename . '.jpg';
	$imagedescription = str_replace("'","\'",$imagedescription);
	echo '<script type="text/javascript">';
	echo "javascript:showPic('$imagefile','$imagethumb','$imagedescription')";
	echo '</script>';
	
	echo $backlink;
}
function PrintSingleGalleryImage($imagedatetime, $imagedescription, $imagethumb, $imagefile)
{
	$currentstyle = GetCurrentStyle();
	echo '<div class="singlegalleryitem">';
	echo "<a href=\"$imagefile\"><img src=\"$imagethumb\" alt=\"$imagedescription\" /></a>";
	echo '<br />';
	echo "$imagedescription";
	echo '</div>';
}

?>