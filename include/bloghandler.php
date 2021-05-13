<?php
include_once("imagehandler.php");
include_once("commenthandler.php");

$blogXml = null;

function PrintSocialNetworkingLinks($shareTitle, $url = null)
{
	$shareContent = "";
	$currentUrl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if($url != null) {
		$currentUrl = $url;
	}
?>
	<a title="Share on Facebook" target="_blank" class="facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $currentUrl; ?>&t=<?php echo $shareTitle; ?>"><i class="fa fa-facebook"></i></a>
	<a title="Share on Twitter" target="_blank" class="twitter" href="https://twitter.com/intent/tweet?text=<?php echo $shareTitle; ?>&url=<?php echo $currentUrl; ?>"><i class="fa fa-twitter"></i></a>
	<a title="Share on Reddit" target="_blank" class="reddit" href="http://reddit.com/submit?url=<?php echo $currentUrl; ?>&title=<?php echo $shareTitle; ?>"><i class="fa fa-reddit"></i></a>
<?php
/*

<a title="Share on Google+" target="_blank" class="google-plus" href="https://plus.google.com/share?url=<?php echo $currentUrl; ?>"><i class="fa fa-google-plus"></i></a>

<a title="Share on tumblr" target="_blank" class="tumblr" href="http://www.tumblr.com/share/link?url=<?php echo $currentUrl; ?>&name=<?php echo $shareTitle; ?>&description=<?php echo $shareContent; ?>"><i class="fa fa-tumblr"></i></a>

	echo '<div style="clear: both;"></div>';
	echo '<div class="social-links">';
	//PrintDeliciousLink($readableid);
	echo '<div class="social-link"><g:plusone></g:plusone></div>';
	PrintTwitterLink($readableid);
	PrintFacebookLink($readableid);
	echo '<div style="clear: both;"></div>';
	echo '</div>';
	echo '<div style="clear: both;"></div>';
	*/
}

function PrintFacebookLink($readableid)
{
	$url = 'http://www.erikmoberg.net/article/' . $readableid;

	echo '<div class="social-link"><fb:like href="' . $url . '" layout="button_count" show_faces="false" width="100" font=""></fb:like></div>';
}

function PrintTwitterLink($readableid)
{
	$shortAddress = GetArticleShortUrl($readableid);
	$text = GetArticleSubHeader($readableid) . ' - ' . GetArticleHeader($readableid);
	echo '<div class="social-link"><a style="margin-left: 10px;" href="http://twitter.com/share" class="twitter-share-button" data-text="' . $text . '" data-count="horizontal" data-via="erikmoberg_swe">Tweet</a></div>';
}

function PrintDeliciousLink($readableid)
{
	$url = "http://delicious.com/save?url=" .
	'http://www.erikmoberg.net/article/' . $readableid .
	"&amp;title=" . myUrlEncode(GetArticleSubHeader($readableid)) . '+-+' . myUrlEncode(GetArticleHeader($readableid));
	echo '<div style="float: left; margin-right: 10px; "><a style="background: none;" href="' . $url . '" target="_blank" rel="nofollow"><img src="delicious_icon_medium.png" alt="Bookmark on Del.icio.us" border="0" /></a></div>';
}

function GetArticleHeader($readableid)
{
	$xml = LoadBlogXml();
	foreach ($xml->entry as $entry)
	{
		if($entry->readableid == $readableid)
		{
			$header = html_entity_decode($entry->header);
			return $header;
		}
	}
}

function GetArticleSubHeader($readableid)
{
	$xml = LoadBlogXml();
	foreach ($xml->entry as $entry)
	{
		if($entry->readableid == $readableid)
		{
			$subheader = html_entity_decode($entry->subheader);
			return $subheader;
		}
	}
}

function GetArticleShortUrl($readableid)
{
	$xml = LoadBlogXml();
	foreach ($xml->entry as $entry)
	{
		if($entry->readableid == $readableid)
		{
			$shorturl = html_entity_decode($entry->shorturl);
			return $shorturl;
		}
	}
}

function GetArticleIntro($readableid)
{
	$xml = LoadBlogXml();
	foreach ($xml->entry as $entry)
	{
		if($entry->readableid == $readableid)
		{
			$intro = html_entity_decode($entry->intro);
			$intro = RemovePseudoLinks($intro);
			$intro = strip_tags($intro);
			$intro = htmlspecialchars($intro);
			$intro = trim( preg_replace( '/\s+/', ' ', $intro ) );
			return $intro;
		}
	}
}

function PrintArticle($readableid)
{
	$article = GetArticle($readableid);

	PrintCompleteEntry(
		$article['readableid'],
		$article['datetime'],
		$article['header'],
		$article['subheader'],
		$article['intro'],
		$article['content'],
		$article['images']);
}

function GetArticle($readableid)
{
	$xml = LoadBlogXml();
	foreach ($xml->entry as $entry)
	{
		if($entry->readableid == $readableid)
		{
			$article = array(
				'datetime' => $entry->date,
				'header' => html_entity_decode($entry->header),
				'subheader' => html_entity_decode($entry->subheader),
				'readableid' => html_entity_decode($entry->readableid),
				'intro' => html_entity_decode($entry->intro),
				'images' => $entry->images,
				'content' => html_entity_decode($entry->content)
				);

			return $article;
		}
	}
}

function PrintNextAndPreviousArticleLinks($readableid, $end)
{
	$xml = loadblogxml();
	$lastentry = null;
	$nextentry = null;
	$onthisentry = false;
	foreach ($xml->entry as $entry)
	{
		if($onthisentry)
		{
			$nextentry = $entry;
			break;
		}
		else if($entry->readableid == $readableid)
		{
			$onthisentry = true;
		}
		else
		{
			$lastentry = $entry;
		}
	}

	echo '<div class="article-navigation">';

	if($nextentry != null)
	{
		echo '<div class="nextArticle">';
		echo '<a href="/article/' . html_entity_decode($nextentry->readableid) . '">Next: ' . html_entity_decode($nextentry->header) . ' <i class="fa fa-chevron-circle-right"></i></a>';
		echo '</div>';
	}

	if($lastentry != null)
	{
		echo '<div class="previousArticle">';
		echo '<a href="/article/' . html_entity_decode($lastentry->readableid) . '"><i class="fa fa-chevron-circle-left"></i> Previous: ' . html_entity_decode($lastentry->header) . '</a>';
		echo '</div>';
	}

	echo '</div>';
}

function PrintRecentBlogHeadings($count)
{
	$xml = LoadBlogXml();
	$noOfEntries = 0;
	$printed = 0;
	foreach ($xml->entry as $entry)
	{
		$noOfEntries++;
	}

	$startAt = $noOfEntries-1;
	$stopAt = $startAt-$count;

	for($i=$startAt;$i>=$stopAt;$i--)
	{
		$entry = $xml->entry[$i];
		$header = html_entity_decode($entry->header);
		$readableid = html_entity_decode($entry->readableid);
		echo '<p><a href="/article/' . $readableid . '">' . $header . "</a></p>\n";
	}
}

function PrintBlogEntries($currentPage)
{
	$noOfEntries = 0;
	$maxEntriesPerPage = 10;
	$xml = LoadBlogXml();
	foreach ($xml->entry as $entry)
	{
		$noOfEntries++;
	}

	$startAt = $noOfEntries-1-($maxEntriesPerPage*($currentPage-1));
	$stopAt = $startAt-$maxEntriesPerPage+1;
	if($stopAt < 0)
		$stopAt = 0;
	for($i=$startAt;$i>=$stopAt;$i--)
	{
		$entry = $xml->entry[$i];
		$datetime = $entry->date;
		$header = html_entity_decode($entry->header);
		$subheader = html_entity_decode($entry->subheader);
		$readableid = html_entity_decode($entry->readableid);
		$intro = html_entity_decode($entry->intro);
		$images = $entry->images;
		$content = html_entity_decode($entry->content);

		PrintIntroEntry($readableid, $datetime, $header, $subheader, $intro, $images, $content, $i, true);
	}

	$noOfPages = $noOfEntries/$maxEntriesPerPage;
	$lastPage = $currentPage >= $noOfPages;
	return $lastPage;
}

function PrintMostCommentedArticlesList()
{
	$xml = LoadBlogXml();
	$entries = array();
	$noOfEntries = 0;
	$count = 5;

	foreach ($xml->entry as $entry)
	{
		$noOfEntries++;
		$readableid = $entry->readableid;
		$noOfComments = GetNoOfCommentsForArticle($readableid);
		$myObject = array('entry' => $entry, 'noOfComments' => $noOfComments);
		array_push($entries, $myObject);
	}

	function cmp($x, $y) {
		return $x['noOfComments'] - $y['noOfComments'];
	}

	usort($entries, 'cmp');

	$startAt = $noOfEntries-1;
	$stopAt = $startAt-$count+1;
	for($i=$startAt;$i>=$stopAt;$i--)
	{
		$entry = $entries[$i]['entry'];
		$header = html_entity_decode($entry->header);
		$readableid = html_entity_decode($entry->readableid);
		echo '<p><a href="/article/' . $readableid . '">' . $header . "</a></p>\n";
	}
}

function LoadBlogXml()
{
	global $blogXml;

	if($blogXml != null) {
		return $blogXml;
	}

	$xmlFile = 'xml/content.xml';
	if (file_exists($xmlFile))
	{
	    $xmlObject = simplexml_load_file($xmlFile);
		if($xmlObject == false)
			exit("Error opening xml file.");

		$blogXml = $xmlObject;
		return $xmlObject;
	}
	else
	{
	    exit("Xml file not found.");
	}
}

function PrintPageList($currentPage) // , $maxEntriesPerPage, $noOfEntries
{
echo '<a id="load-more-entries" class="btn" href="/index/' . ($currentPage+1) . '" data-pagenumber="' . ($currentPage+1) . '">Load more entries</a>';

return;

}

function PrintIntroEntry($readableid, $datetime, $header, $subheader, $intro, $images, $content, $introno, $isListview)
{
	$isLongArticle = trim($content) != '';

	$intro = '<p>' . $intro . '</p>';

	$intro = AddSpecialTags($intro, $readableid, $images, false, false, $introno, false, false, true);
	echo '<article><div class="articleintro">';
	echo '<h2><a href="/article/' . $readableid . '">' . $header . "</a></h2>\n";
	echo '<h3>' . $subheader . "</h3>\n";
	$time = strtotime($datetime);
	echo "<h4>" . date('F dS, Y', $time) . "</h4>\n";
	echo $intro . "\n";

	$noOfComments = GetNoOfCommentsForArticle($readableid);
	$commentstring = '';
	if($noOfComments == 0)
		$commentstring = 'Comment/Share';
	else if($noOfComments == 1)
		$commentstring = $noOfComments . ' comment';
	else
		$commentstring = $noOfComments . ' comments';

	echo '<div style="clear: both;">';

	echo '<div class="articlecommentlink">';
	echo '<a href="/article/' . $readableid . '#comments"><i class="fa fa-comment"></i> ' . $commentstring . '</a>';
	echo '</div>';

	if($isLongArticle) {
		echo '<div class="fullarticlelink">';
		echo '<a href="/article/' . $readableid . '"><i class="fa fa-chevron-circle-right"></i> Read the full article</a>' . "\n";
		echo '</div>';
	}

	echo '</div>';
	echo '<div class="clearfix"></div>';
	echo '</div></article>';

}

function PrintCompleteEntry($readableid, $datetime, $header, $subheader, $intro, $content, $images)
{
	$isLongArticle = trim($content) != '';
	$intro = '<p>' . $intro . '</p>';
	$intro = AddSpecialTags($intro, $readableid, $images, false, false, 0, !$isLongArticle, $isLongArticle, false);
	if($isLongArticle) {
		$content = AddSpecialTags($content, $readableid, $images, true, true, 1, false, false, false);
	}
	echo '<div class="full-article">';
	echo '<div class="articleintro full">';
	echo '<h2>' . $header . "</h2>\n";
	echo '<h3>' . $subheader . "</h3>\n";
	$time = strtotime($datetime);
	echo '<h4>' . date('F jS, Y', $time) . "</h4>\n";
	echo $intro . "\n";
	echo '</div>';
	echo $content . "\n";
	echo '</div>';
}

function AddSpecialTags($text, $readableid, $images, $clearImage, $includeImageDescription, $introno, $addImagesLast, $removeImages, $printTeasers)
{
	$thumbNo = 0;

	//$text = str_replace('[note]','<div class="note"><div class="noteicon"><i class="fa fa-hand-o-right"></i></div><div class="notecontent">',$text);
    $text = str_replace('[note]','<div class="note"><div class="notecontent">',$text);
	$text = str_replace('[/note]','</div></div>',$text);

	while(strrpos($text,'[image') !== false)
	{
		$thumbNo++;

		// Get complete pseudo tag
		$startOfTagIndex = strrpos($text,'[image');
		$endOfTagIndex = strrpos($text,']');
		$pseudotag = substr($text,$startOfTagIndex,$endOfTagIndex-$startOfTagIndex+1);

		// Get name of image
		$imagename = substr($pseudotag,7,$endOfTagIndex-$startOfTagIndex-7);

		// Get additional image information
		$imagedescription = '';
		$imagefileextension = '';
		foreach ($images->image as $image)
		{
			if($image->name == $imagename)
			{
				$imagedescription = $image->description;
				$imagefileextension = $image->extension;
			}
		}

		$completebigimagename = '/blogimages/' . $readableid . '/' . $imagename . $imagefileextension;
		$completesmallimagename = '/blogimages/' . $readableid . '/' . $imagename . '_thumb' . $imagefileextension;

		// Create html tag
		$htmltag = '';
		if($addImagesLast) {
			$htmltag = GetSingleImageMarkup($imagedescription, $completebigimagename, $completesmallimagename, $clearImage, $includeImageDescription, $thumbNo, $introno);
			$text = str_replace($pseudotag,'',$text);
			$text .= $htmltag;
		}
		else if($removeImages) {
			$text = str_replace($pseudotag,'',$text);
		}
		else if($printTeasers) {
			// printTeasers: post is a part of the listing on the first page.
			$htmltag = "<div class=\"teaser\"><a href=\"/article/$readableid\"><img src=\"$completesmallimagename\" alt=\"$imagedescription\" /></a></div>";
			$text = $htmltag . str_replace($pseudotag,'',$text);
		}
		else {
			$htmltag = GetSingleImageMarkup($imagedescription, $completebigimagename, $completesmallimagename, $clearImage, $includeImageDescription, $thumbNo, $introno);
			// Replace pseudo tag with html tag
			$text = str_replace($pseudotag,$htmltag,$text);
		}
	}
	return $text;
}

function GetSingleImageMarkup($imagedescription, $imagefile, $imagethumb, $clearImage, $includeImageDescription, $thumbNo, $introno)
{
	$includeImageDescription = true;
	$markup = '<div class="articleimage">';
	$markup .= "<figure><img src=\"$imagefile\" alt=\"$imagedescription\" />";
	if($includeImageDescription)
	{
		$markup .= '<figcaption><div class="imagedescription">' . $imagedescription . '</div></figcaption>';
	}
	$markup .= '</figure></div>';
	return $markup;
}

function GetEntriesAsRss()
{
	$dateFormat = 'D, d M Y H:i:s O';
	$xml = LoadBlogXml();

	$lastBuildDate = ''; // when last article was added
	$pubDate = ''; // when first article was ever published

	$entries = '';
	foreach ($xml->entry as $entry)
	{
		$datetime = $entry->date;
		$datetime = strtotime($datetime);
		$datetime = date($dateFormat, $datetime);

		$lastBuildDate = $datetime;
		if ($pubDate == '') {
			$pubDate = $datetime;
		}

		$newentry = '
   <item>
    <title>' . $entry->header . '</title>
    <description>' . CreateIntroRssEntry($entry->intro) . '</description>
    <link>https://www.erikmoberg.net/article/' . $entry->readableid . '</link>
	<guid>https://www.erikmoberg.net/article/' . $entry->readableid . '</guid>
    <pubDate>' . $datetime . '</pubDate>
   </item>';
		$entries = $newentry . $entries;
	}

	$content = '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
 <channel>
  <title>erikmoberg.net blog</title>
  <link>https://www.erikmoberg.net</link>
  <description>Erik Moberg\'s personal homepage</description>
  <image>
   <url>https://www.erikmoberg.net/content/rss-logo.png</url>
   <title>erikmoberg.net blog</title>
   <link>https://www.erikmoberg.net</link>
  </image>
  <lastBuildDate>' . $lastBuildDate . '</lastBuildDate>
  <pubDate>' . $pubDate . '</pubDate>';

	$content .= $entries;

	$content .= '
 </channel>
</rss>';
	return $content;
}

function CreateIntroRssEntry($introtext)
{
	$introtext = MakeReferencesGlobal($introtext, '<a href="');
	$introtext = MakeReferencesGlobal($introtext, '<img src="');
	$introtext = RemovePseudoLinks($introtext);
	$introtext = htmlspecialchars($introtext);
	return $introtext;
}

function RemovePseudoLinks($text)
{
	while(strrpos($text,'[image') !== false)
	{
		// Get complete pseudo tag
		$startOfTagIndex = strrpos($text,'[image');
		$endOfTagIndex = strrpos($text,']');
		$pseudotag = substr($text,$startOfTagIndex,$endOfTagIndex-$startOfTagIndex+1);
		$text = str_replace($pseudotag,'',$text);
	}
	return $text;
}

function MakeReferencesGlobal($introtext, $stringToReplace)
{
	$currentIndex = 0;

	while(true)
	{
		$startOfTagIndex = strrpos($introtext,$stringToReplace, $currentIndex);
		if($startOfTagIndex < 0 || !$startOfTagIndex)
			break;

		$startOfTagIndex += strlen($stringToReplace);

		$currentIndex = $startOfTagIndex;
		$endOfTagIndex = strrpos($introtext,'"',$startOfTagIndex+1);
		$oldlink = substr($introtext, $startOfTagIndex, $endOfTagIndex - $startOfTagIndex);
		$newlink = $oldlink;

		if(!(strpos($oldlink, 'http://') === 0) && !(strpos($oldlink, 'https://') === 0))
		{
			$newlink = 'http://www.erikmoberg.net';
			if((strpos($oldlink, '/') === 0))
				$newlink .= $oldlink;
			else
				$newlink .= '/' . $oldlink;
		}

		$introtext = str_replace($oldlink,$newlink,$introtext);
	}

	return $introtext;
}

function myUrlEncode($string)
{
	$string = str_replace("+", '%2B', $string);
    $entities = array(    '%21', '%2A', '%27', '%28',    '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!',   '*',   "'",   "(", ")", ";",   ":",   "@",   "&",   "=",    "+", "$", ",", "/", "?", "%", "#", "[", "]");
    $result = str_replace($entities, $replacements, urlencode($string));
	return $result;
}
?>
