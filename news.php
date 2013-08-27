<?php

header("Location: /");

include_once("include/webdesign.php");
PrintStartHtml('News',4);
?>

<?php

$feed = 1;
if(isset($_GET['feed']))
	$feed = $_GET['feed'];
	
switch($feed)
{
	case 1:
		$feedName = 'DN.se';
		$xml = simplexml_load_file("http://dn.se/m/rss/toppnyheter");
	break;
	case 2:
		$feedName = 'Wired';
		$xml = simplexml_load_file("http://feeds.wired.com/wired/index?format=xml");
	break;
	case 3:
		$feedName = 'Rimnytt';
		$xml = simplexml_load_file("http://rimnytt.se/feed/");
	break;
	case 4:
		$feedName = 'Skandic Inkasso';
		$xml = simplexml_load_file("http://skandicinkasso.de/language/rss-en.xml");
	break;
	case 5:
		$feedName = 'CityDeal';
		$xml = simplexml_load_file("http://www.citydeal.de/RSSRome.action");
	break;
	default:
		$feedName = 'Slashdot';
		$xml = simplexml_load_file("http://rss.slashdot.org/Slashdot/slashdot.rdf");
	break;
}

if($xml->item == '')
	$xml = $xml->channel;

$style = GetCurrentStyle();
print <<<END
<p>Viewing newsfeed: $feedName<br />
Change feed: <a href="?style=$style&feed=1">DN.se</a> - <a href="?style=$style&feed=0">Slashdot</a> - <a href="?style=$style&feed=2">Wired</a> - <a href="?style=$style&feed=4">Skandic Inkasso</a> - <a href="?style=$style&feed=5">CityDeal</a>
</p>
END;


foreach($xml->item as $newsitem)
{
	$link = $newsitem->link;
	echo "<h2><a href=\"$link\">" . utf8_decode($newsitem->title) . '</a></h2>';
	echo '<p>' . utf8_decode($newsitem->description) . "</p>\n";
}

?>

<?php
PrintEndHtml();
?>