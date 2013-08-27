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
	echo "<h2>Image gallery</h2>";
	echo '<p>Some pictures, mostly from my trips.</p>';
	echo '<p>Looking for high resolution images for printing? You can find some on the <a href="download.php">download page</a>.</p>';
	foreach ($xml->gallery as $gallery)
	{
		if($gallery->archived != 'true')
		{
			$galleryname = $gallery->name;
			$gallerydatetime = $gallery->datetime;
			$galleryfrontpic = '/images/' . $gallery->basedir . '/' . $gallery->frontpic;
			$galleryid = $gallery['id'];
			PrintMainGalleryImage($galleryname, $gallerydatetime, $galleryfrontpic, $galleryid);
		}
	}
	
	echo '<div style="clear: both"></div>';
	echo '<p>Missing something? More pictures can be found in the <a href="/archivedimages">archive</a>.</p>';
}

function DisplayArchivedGallery()
{
	$xml = LoadImageXml();
	echo "<h2>Archived galleries</h2>";
	echo '<p>Some older pictures I have decided to archive here.</p>';
	foreach ($xml->gallery as $gallery)
	{
		if($gallery->archived == 'true')
		{
			$galleryname = $gallery->name;
			$gallerydatetime = $gallery->datetime;
			$galleryfrontpic = '/images/' . $gallery->basedir . '/' . $gallery->frontpic;
			$galleryid = $gallery['id'];
			PrintMainGalleryImage($galleryname, $gallerydatetime, $galleryfrontpic, $galleryid);
		}
	}
}

function PrintMainGalleryImage($galleryname, $gallerydatetime, $galleryfrontpic, $galleryid)
{
	echo '<div class="maingalleryitem">';
	echo "<a href=\"/images/$galleryid\"><img src=\"$galleryfrontpic\" alt=\"$galleryname\" /></a>";
	echo "<div class=\"maingalleryitemcaption\"><a href=\"/images/$galleryid\">$galleryname</a></div>";
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
	$backlink = '<p><a href="/images" class="backToImageGallery">Back to overview</a></p>';
	echo $backlink;

	$imagebasedir = '/images/' . $gallerynode->basedir;
	$thumbNo = 1;
	foreach($gallerynode->images->image as $image)
	{
		$imagedescription = $image->description;
		$basefilename = $imagebasedir . '/' . $image->basefilename;
		$imagethumb = $basefilename . '_thumb.jpg';
		$imagethumb_small = $basefilename . '_thumb_small.jpg';
		$imagefile = $basefilename . '.jpg';
		
		//echo '<div class="thumb_small_gallery">';
		echo "<a id=\"thumb$thumbNo\" href=\"$imagefile\" class=\"highslide\" onclick=\"return hs.expand(this)\">";
		echo "<img src=\"$imagethumb\" alt=\"Highslide JS\" title=\"Click to enlarge\" />";
		echo "</a>";
		echo '<div class="highslide-caption">';
		echo $imagedescription;
		echo '</div>';
		//echo '</div>';
		$thumbNo++;
	}
	
	echo '<div style="clear:both;"></div>';
	echo $backlink;
}
function PrintSingleGalleryImage($imagedatetime, $imagedescription, $imagethumb, $imagefile)
{
	echo '<div class="singlegalleryitem">';
	echo "<a href=\"$imagefile\"><img src=\"$imagethumb\" alt=\"$imagedescription\" /></a>";
	echo '<br />';
	echo "$imagedescription";
	echo '</div>';
}

?>