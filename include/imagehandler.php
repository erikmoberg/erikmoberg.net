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
	echo '<div class="page-section">';
	echo "<h2>Image gallery</h2>";
	echo '<p>Some pictures, mostly from my trips.</p>';
	echo '<p>Looking for high resolution images for printing? You can find some on the <a href="/download">download page</a>.</p>';
	echo '</div>';
	echo '<div class="page-section">';
    
    echo '<div>';
	foreach ($xml->gallery as $gallery)
	{
		if($gallery->archived != 'true')
		{
			$galleryname = $gallery->name;
			$gallerydatetime = $gallery->datetime;
			$galleryfrontpic = '/images/' . $gallery->basedir . '/' . $gallery->frontpic;
			$galleryid = $gallery['id'];
            $gallerdescription = $gallery->description;
			PrintMainGalleryImage($galleryname, $gallerydatetime, $galleryfrontpic, $galleryid, $gallerdescription);
		}
	}
    echo '</div>';

	echo '<div style="clear: both"></div>';
	echo '</div>';
	echo '<div class="page-section">';
	echo '<p>Missing something? More pictures can be found in the <a href="/archivedimages">archive</a>.</p>';
	echo '</div>';
}

function DisplayArchivedGallery()
{
	$xml = LoadImageXml();
	echo '<div class="page-section">';
	echo "<h2>Archived galleries</h2>";
	echo '<p>Some older pictures I have decided to archive here!</p>';
	echo '</div>';
	echo '<div class="page-section">';
	foreach ($xml->gallery as $gallery)
	{
		if($gallery->archived == 'true')
		{
			$galleryname = $gallery->name;
			$gallerydatetime = $gallery->datetime;
			$galleryfrontpic = '/images/' . $gallery->basedir . '/' . $gallery->frontpic;
			$galleryid = $gallery['id'];
            $gallerdescription = $gallery->description;
			PrintMainGalleryImage($galleryname, $gallerydatetime, $galleryfrontpic, $galleryid, $gallerdescription);
		}
	}

	echo '<div style="clear: both"></div>';
	echo '</div>';
}

function PrintMainGalleryImage($galleryname, $gallerydatetime, $galleryfrontpic, $galleryid, $gallerdescription)
{
    $version = "4";
	echo '<div class="maingalleryitem">';
	echo "<a href=\"/images/$galleryid\"><img src=\"$galleryfrontpic?_=$version\" alt=\"$galleryname\" />";
    echo "<h3 class=\"maingalleryitemname\">$galleryname</h3>";
    echo "<div class=\"maingalleryitemcaption\">$gallerdescription</div>";
    echo "</a>";
	echo '</div>';
}

function DisplaySingleGallery($galleryid)
{
    $version = "4";
	$xml = LoadImageXml();
	foreach($xml->gallery as $gallery)
	{
		if($gallery['id'] == $galleryid)
			$gallerynode = $gallery;
	}
	$galleryname = $gallerynode->name;
	$gallerdescription = $gallerynode->description;
	echo '<div class="page-section">';
	echo "<h2>Gallery: $galleryname</h2>";
	echo "<p>$gallerdescription</p>";
	$backlink = '<p><a href="/images">' . GetIconMarkup('arrow-left-circle') . ' Back to overview</a></p>';
	echo $backlink;
	echo '</div>';
	echo '<div class="page-section">';
    echo '<div class="responsive-grid-3-columns responsive-grid-no-lineheight responsive-grid-no-justify image-gallery-grid">';

	$imagebasedir = '/images/' . $gallerynode->basedir;
	$thumbNo = 1;
	foreach($gallerynode->images->image as $image)
	{
		$imagedescription = trim($image->description);
		$basefilename = $imagebasedir . '/' . $image->basefilename;
		$imagethumb = $basefilename . '_thumb.jpg?_=' . $version;
		$imagethumb_small = $basefilename . '_thumb_small.jpg?_=' . $version;
		$imagefile = $basefilename . '.jpg?_=' . $version;

		echo "<a href=\"$imagefile\" class=\"gallery-thumbnail\">";
		echo "<img src=\"$imagethumb\" alt=\"" . htmlentities(htmlentities($imagedescription, ENT_QUOTES, "UTF-8"), ENT_QUOTES, "UTF-8") . "\" title=\"" . htmlentities(htmlentities($imagedescription, ENT_QUOTES, "UTF-8"), ENT_QUOTES, "UTF-8") . " (Click to enlarge)\" />";
		echo "</a>";
		$thumbNo++;
	}

	echo '<div style="clear:both;"></div>';
	echo '</div>';
    echo '</div>';
	echo '<div class="page-section">';
	echo $backlink;
	echo '</div>';
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
