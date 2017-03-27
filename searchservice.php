<?php

$query = 'test';
if(isset($_GET['q'])) {
	$query = $_GET['q'];
} else {
  ?>
  <p>No query passed.</p>
  <?php
}

$apiKey = 'AIzaSyBxVvcApx2TK2r97lwoLlk-iLroS-R6oQc';
$apiUrl = 'https://www.googleapis.com/customsearch/v1';
$customSearchEngineId = '011133465101985440007:knk5ncd4l7e';
$url = $apiUrl . "?key=" . $apiKey . "&cx=" . $customSearchEngineId . "&q=" . rawurlencode($query);
$string = file_get_contents($url);
$json_a = json_decode($string, true);

if(count($json_a["items"]) == 0) {
  ?><p class="gsc-search-entry">Search gave no results.</p><?php
  return;
}

foreach ($json_a["items"] as $item) {
  $imageSrc = $item["pagemap"]["cse_image"][0]["src"];
  ?>
  <div class="gsc-search-entry">
    <?php
    if($imageSrc != null) {
      ?>
      <a href="<?php echo $item["link"]; ?>">
        <img src="<?php echo $imageSrc ?>" alt="<?php echo $item["title"]; ?>" />
      </a>
      <?php
    }
    ?>
    <a class="gsc-title" href="<?php echo $item["link"]; ?>">
      <?php echo $item["title"]; ?>
    </a>
    <div class="gsc-snippet">
      <?php echo str_replace('<br>', "", $item["htmlSnippet"]); ?>
    </div>
    <div class="clearfix"></div>
  </div>
  <?php
}
?>
