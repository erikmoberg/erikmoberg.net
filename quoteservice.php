<?php
$rssurl = 'http://quotes4all.net/rss/560210110/quotes.xml';
$xmlObject = simplexml_load_file($rssurl);
$items = $xmlObject->xpath('/rss/channel/item');
$entryIndex = rand(0, count($items) - 1);
$item = $items[$entryIndex];
$text = $item->description;
$name = $item->title;
?>

<div id="quotecontainer">
  <p><?php echo $text; ?></p>
  <p>-- <?php echo $name; ?></p>
</div>
<div class="clearfix"></div>
