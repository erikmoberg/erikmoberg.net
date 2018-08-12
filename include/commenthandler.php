<?php

$commentXml = null;

function LoadCommentXml()
{
	global $commentXml;

	if($commentXml != null) {
		return $commentXml;
	}

	$xmlFile = 'xml/comments.xml';
	if (file_exists($xmlFile))
	{
	    $commentXml = simplexml_load_file($xmlFile);
		return $commentXml;
	}
	else
	{
	    exit('Failed to open $xmlFile.');
	}
}

function GetNoOfCommentsForArticle($articlename)
{
	$noOfComments = 0;
	$xml = LoadCommentXml();
	$comments = $xml->xpath("/articlecomments/comments[@articlename='$articlename']/comment");
	for($i=0; !empty($comments) && $i < count($comments);$i++)
	{
		$noOfComments++;
	}
	return $noOfComments;
}

function PrintComments($articlename)
{
	$xml = LoadCommentXml();
	$comments = $xml->xpath("/articlecomments/comments[@articlename='$articlename']/comment");
	for($i=0;!empty($comments) && $i < count($comments);$i++)
	{
		$comment = $comments[$i];
		$name = htmlspecialchars($comment->name);
		$datetime = $comment->datetime;
		$datetime = strtotime($datetime);
		$datetime = date('F jS, Y H:i', $datetime);
		$website = htmlspecialchars($comment->website);
		$message = htmlspecialchars($comment->message);
		$message = AddLinksToMessage($message);
		$markup = GetSingleCommentMarkup($name, $datetime, $website, $message, $i);
		echo $markup;
	}
}

function PrintRecentComments($count)
{
	$xml = LoadCommentXml();
	$articles = $xml->xpath("/articlecomments/comments");
	$entries = array();
	//var_dump($articles);
	for($j=0;!empty($articles) && $j < count($articles);$j++)
	{
		$comments = $articles[$j]->comment;
		$readableid = $articles[$j]->attributes()->articlename;

		for($i=0;!empty($comments) && $i < count($comments);$i++)
		{
			$comment = $comments[$i];
			$datetime = strtotime($comment->datetime);
			$myObject = array(
				'comment' => $comment,
				'datetime' => $datetime,
				'readableid' => $readableid,
				'number' => $i
			);

			array_push($entries, $myObject);
		}
	}

	function cmp1($x, $y) {
		return $x['datetime'] - $y['datetime'];
	}

	usort($entries, 'cmp1');

	for($i = count($entries)-1; $i >= 0 && $i >= count($entries)-$count;$i--)
	{
		$comment = $entries[$i]['comment'];
		$name = htmlspecialchars($comment->name);
		$datetime = $comment->datetime;
		$datetime = strtotime($datetime);
		$datetime = date('M dS, Y', $datetime);
		$message = $comment->message;
		$maxlength = 50;
		$message = $comment->message;
		$message = strlen($message) > $maxlength ? substr($message, 0, $maxlength) . '...' : $message;
		$message = htmlspecialchars($message);
		$readableid = $entries[$i]['readableid'];
		$number = $entries[$i]['number'];
		echo '<p><a href="/article/' . $readableid . '#comment-' . $number . '" style="font-size: 11px;color: #888;">' . $name . ', ' . $datetime . '</a><br /><a href="/article/' . $readableid . '#comment-' . $number . '">' . $message . "</a></p>\n";
	}
}

function AddLinksToMessage($message)
{
	return preg_replace('@((https?://){1}([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', '<a href="$1" rel="nofollow" target="_blank">$1</a>', $message);
}

function GetSingleCommentMarkup($name, $datetime, $website, $message, $number)
{
	$markup = '';
	$markup .= '<div class="commententry page-section" id="comment-' . $number . '">';
	$markup .= '<div class="commentheader">';
	$markup .= '<div class="commenttime">';
	$markup .= $datetime;
	$markup .= '</div>';
	$markup .= '<div class="commentname"><i class="fa fa-comment"></i> ';
	if($website != null && $website != '') {
		$markup .= '<a href="' . $website . '" target="_blank">' . $name . '</a>';
	}
	else {
		$markup .= $name;
	}
	$markup .= '</div>';
	$markup .= '</div>';
	$markup .= '<div class="commentmessage">';
	$message = $message;
	$message = str_replace("\n","<br />",$message);
	$markup .= "<p>" . $message . "</p>";
	$markup .= '</div>';
	$markup .= '</div>';
	return $markup;
}

function AddComment($articlename, $name, $website, $message)
{
	if($articlename != null && $articlename != "" && $name != null && $name != "" && $message != null  && $message != "")
	{
		$commentId = GetNoOfCommentsForArticle($articlename);
		$name = htmlspecialchars($name);
		$message = htmlspecialchars($message);

		if($website != null && $website != '') {
			$website = CreateValidUrl($website);
		}

		$website = htmlspecialchars($website);

		$t = getdate();

		$datetime = date('Y-m-d H:i',$t[0]);

		$xml = LoadCommentXml();

		$articleComments = $xml->xpath("/articlecomments/comments[@articlename='$articlename']");

		// Do comments for the article exist?
		if( $articleComments == null)
		{
			$articleComments  = $xml->addChild('comments');
			$articleComments ->addAttribute('articlename', $articlename);
		}
		else
		{
			$articleComments = $articleComments[0];
		}

		$comment = $articleComments->addChild('comment');

		$comment->addChild('name', $name);
		$comment->addChild('datetime', $datetime);
		$comment->addChild('website', $website);
		$comment->addChild('message', $message);

		$doc = new domDocument('1.0');
		$someXml = $xml->asXML();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->loadXML($someXml);

		//////////!!!!!!!!!!!

		$doc->save('xml/comments.xml');

		//////////!!!!!!!!!!!


		$to = "erik@erikmoberg.net";
		$subject = "erikmoberg.net comment - $articlename - $name";
		$message = "$message";
		$header = "From: erik@erikmoberg.net";

		$header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";

		//////////!!!!!!!!!!!

		mail($to, "=?UTF-8?B?".base64_encode($subject).'?=', $message, $header_ . $header);

		//////////!!!!!!!!!!!

		return $commentId;
	}

	return -1;
}

function CreateValidUrl($website)
{
	$website = strtolower($website);
	if(!(strpos($website, 'http://') === 0))
		$website = 'http://' . $website;
	return $website;
}

function GetCommentsAsRss($article, $readableid)
{
	$dateFormat = 'D, d M Y H:i:s O';
	$xml = LoadCommentXml();
	$comments = $xml->xpath("/articlecomments/comments[@articlename='$readableid']/comment");
	$pubDate = $article['datetime']; // when article was published
	$pubDate = strtotime($pubDate);
	$pubDate = date($dateFormat, $pubDate);
	$lastBuildDate = $pubDate; // when last comment was added

	$content = '';
	$entries = '';

	for($i=0;!empty($comments) && $i < count($comments);$i++)
	{
		$comment = $comments[$i];
		$name = htmlspecialchars($comment->name);
		$datetime = $comment->datetime;
		$datetime = strtotime($datetime);
		$datetime = date($dateFormat, $datetime);
		$message = htmlspecialchars(AddLinksToMessage($comment->message));
		$lastBuildDate = $datetime;
		//$guid = hash('ripemd160', $name . $datetime . $message);
		$link = 'https://www.erikmoberg.net/article/' . $readableid . '#comment-' . $i;

		$newentry = '
   <item>
    <title>' . $name . '</title>
    <description>' . $message . '</description>
    <link>' . $link . '</link>
    <guid>' . $link . '</guid>
    <pubDate>' . $datetime . '</pubDate>
   </item>';
		$entries = $newentry . $entries;
	}

	$intro = '';
	$intro .= '<?xml version="1.0" encoding="utf-8"?>
 <rss version="2.0">
 <channel>
  <title>erikmoberg.net comments for the article ' . $article['header'] . '</title>
  <link>https://www.erikmoberg.net/article/' . $readableid . '</link>
  <description>Comments for the article ' . $article['header'] . ' from erikmoberg.net</description>
  <image>
   <url>https://www.erikmoberg.net/content/images/rss-logo.png</url>
   <title>erikmoberg.net comments for the article ' . $article['header'] . '</title>
   <link>https://www.erikmoberg.net/article/' . $readableid . '</link>
  </image>
  <lastBuildDate>' . $lastBuildDate . '</lastBuildDate>
  <pubDate>' . $pubDate . '</pubDate>';

	$content .= $intro . $entries;
	$content .= "\n </channel>\n";
	$content .= "</rss>\n";
	return $content;
}

?>
