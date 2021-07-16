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
        $extension = '.jpg';
        if (!file_exists('hires/' . $entry->BaseFileName . $extension)) {
            $extension = '.png';
        }
        
		$title = $entry->Title;
		$thumbfilename = '/hires/' . $entry->BaseFileName . '_thumb' . $extension;
		$filename = '/hires/' . $entry->BaseFileName . $extension;
        $comment = $entry->Comment;
		PrintImage($title, $thumbfilename, $filename, $comment);
	}
}

function PrintImage($title, $thumbfilename, $filename, $comment)
{
	$link = '<a href="' . $filename . '" target="_blank">';
    ?>
      <div class="hires_image">
        <h3><?php echo $link; ?> <?php echo $title; ?></h3>
        <?php echo $link; ?>
          <img alt="<?php echo $title; ?>" src="<?php echo $thumbfilename; ?>?_=1" />
        </a>
      <figcaption><?php echo $comment; ?></figcaption>
      </div>
    <?php
}

?>