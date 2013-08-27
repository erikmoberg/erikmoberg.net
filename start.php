<?php
//ini_set('display_errors', '1');
include_once("include/webdesign.php");
include_once("include/bloghandler.php");
//PrintStartHtml('Home',0);

$page = 1;
if(isset($_GET['page']))
	$page = $_GET['page'];

PrintBlogEntries($page);

//PrintEndHtml();
?>