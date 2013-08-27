<?php
function LoadHiresImageXml()
{
	$xmlFile = 'xml/hires.xml';
	if (file_exists($xmlFile))
	{
	    return simplexml_load_file($xmlFile); 
	}
	else
	{
	    exit('Failed to open $xmlFile.');
	}
}

function DisplayHiresImages()
{
	$xml = LoadHiresImageXml();
	foreach ($xml->HiresImageEntry as $entry)
	{
		$title = $entry->Title;
		$thumbfilename = '/hires/' . $entry->BaseFileName . '_thumb.jpg';
		$filename = '/hires/' . $entry->BaseFileName . '.jpg';
		PrintImage($title, $thumbfilename, $filename);
	}
}

function PrintImage($title, $thumbfilename, $filename)
{
	$link = '<a href="' . $filename . '" target="_blank">';
	echo '<div class="hires_image">' . $link . '<img src="' . $thumbfilename . '" /></a><br />' . $link . $title . '</a></div>';
}

?>