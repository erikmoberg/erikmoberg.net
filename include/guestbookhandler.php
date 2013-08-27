<?php
function LoadGuestbookXml()
{
	$xmlFile = 'xml/guestbook.xml';
	if (file_exists($xmlFile))
	{
	    return simplexml_load_file($xmlFile); 
	}
	else
	{
	    exit('Failed to open $xmlFile.');
	}
}
function PrintEntries($currentPage)
{
	$noOfEntries = 0;
	$maxEntriesPerPage = 10;
	$xml = LoadGuestbookXml();
	foreach ($xml->entries->entry as $entry)
	{
		$noOfEntries++;
	}
	$startAt = $noOfEntries-1-($maxEntriesPerPage*($currentPage-1));
	$stopAt = $startAt-$maxEntriesPerPage+1;
	if($stopAt < 0)
		$stopAt = 0;
	for($i=$startAt;$i>=$stopAt;$i--)
	{
		$entry = $xml->entries->entry[$i];
		$username = htmlspecialchars($entry->username);
		$datetime = $entry->datetime;
		//$email = $entry->email; //Do not display
		$message = htmlspecialchars($entry->message);
		$message = AddLinksToMessage($message);
		$website = htmlspecialchars($entry->website);
		PrintSinglePost($username, $website, $datetime, $message);
	}
	PrintPageList($currentPage, $maxEntriesPerPage, $noOfEntries);
}
function PrintSinglePost($username, $website, $datetime, $message)
{
	echo '<div class="guestbookentry">';
	echo '<div class="guestbookheader">';
	if($website != null && $website != '')
		echo '<a href="' . $website . '">' . $username . '</a>';
	else
		echo $username;
	echo ', ' . $datetime;
	echo '</div>';
	echo '<div class="guestbookmessage">';
	$message = $message;
	$message = str_replace("\n","<br />",$message);
	echo "<p>" . $message . "</p>";
	echo '</div>';
	echo '</div>';
}

function AddLinksToMessage($message)
{
	return preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', '<a href="$1" rel="nofollow" target="_blank">$1</a>', $message);
}

function PrintPageList($currentPage, $maxEntriesPerPage, $noOfEntries)
{
	//Round up
	$noOfPages = $noOfEntries/$maxEntriesPerPage;
	if($noOfPages > 0)
		echo 'Pages: ';
	for($i=0;$i<$noOfPages;$i++)
	{
		if(($i+1) == $currentPage)
			echo ($i+1);
		else
			echo '<a href="guestbook.php?page=' . ($i+1) . '">' . ($i+1) . '</a>';
		
		if($i<$noOfPages-1)
			 echo ' - ';
	}
}
function AddPost($name, $website, $email, $message, $spamTest)
{
	if($name != "" && $name != null && $email != "" && $email != null && $message != "" && $message != null && ($spamTest == null || $spamTest == ""))
	{	
		$name = htmlspecialchars($name);
		$email = htmlspecialchars($email);
		$message = htmlspecialchars($message);
		
		if($website != null && $website != '')
			$website = CreateValidUrl($website);
			
		$website = htmlspecialchars($website);
		
		$t = getdate();
		
	    $datetime = date('Y-m-d H:i',$t[0]);
		
		$xml = LoadGuestbookXml();
		
		$entry = $xml->entries[0]->addChild('entry');
		$entry->addChild('username', $name);
		$entry->addChild('website', $website);
		$entry->addChild('datetime', $datetime);
		$entry->addChild('email', $email);
		$entry->addChild('message', $message);
		
		$doc = new domDocument('1.0');
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$someXml = $xml->asXML();
		$doc->loadXML($someXml);
		$doc->save('xml/guestbook.xml');
		
		$to = "erikmoberg@hotmail.com";
		$subject = "erikmoberg.net guestbook - $name";
		$message = "$message";
		$header = "From: erikmoberg";
		
		$header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
		mail($to, "=?UTF-8?B?".base64_encode($subject).'?=', $message, $header_ . $header);
	}
	echo" <script>window.location=\"guestbook.php?message=posted\"</script> ";
}

function CreateValidUrl($website)
{
	$website = strtolower($website);
	if(!(strpos($website, 'http://') === 0))
		$website = 'http://' . $website;
	return $website;
}

?>