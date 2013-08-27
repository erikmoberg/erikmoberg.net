<?php 
include_once("include/bloghandler.php");
include_once("include/commenthandler.php");

if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
    ini_set("zlib.output_compression", 1);
}
header('Content-Type: text/xml; charset=utf-8');
if(isset($_GET['article'])) {
	$readableid = $_GET['article'];
	$readableid = str_replace('.xml', '', $readableid);
	$article = GetArticle($readableid);
	if($article == null) {
		echo 'Article not found.';
	}
	echo GetCommentsAsRss($article, $readableid);
}
else {
	echo GetEntriesAsRss();
}
?>