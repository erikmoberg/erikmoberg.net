<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("bloghandler.php");
require_once("commenthandler.php");

function GetQueryString()
{
	$s = '?';
	foreach($_GET as $variable => $value)
	{
		$s .= "$variable=$value&amp;";
	}
	return $s;
}

function PrintStartHtml($pageTitle, $highlightitem, $metadescription)
{
	$isFrontPage = false; //$highlightitem == 0
	date_default_timezone_set('Europe/Berlin');
	$pageFileName = '';
	$metadescription = "Erik Moberg's personal homepage - " . ($metadescription == null ? "photography, travelling, web design and more" : $metadescription);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $pageTitle; ?> - Erik Moberg's personal homepage - photography, gadgets, DIY, and more</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,400i,700|Roboto' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="/content/styles/style.css.php?_=8" />
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
	<link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="icon" type="image/png" href="/content/favicon/favicon-192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="/content/favicon/favicon-160.png" sizes="160x160">
	<link rel="icon" type="image/png" href="/content/favicon/favicon-96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="/content/favicon/favicon-16.png" sizes="16x16">
	<link rel="icon" type="image/png" href="/content/favicon/favicon-32.png" sizes="32x32">
	<link rel="alternate" type="application/rss+xml" href="/rss.php" title="erikmoberg.net" />
	<meta name="robots" content="index, follow" />
	<meta name="googlebot" content="index, follow" />
	<meta name="description" content="$metadescription" />
	<meta name="keywords" content="Erik Moberg, photography, cameras, travel, web design, web development, programming, personal blog, php, css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php
if($isFrontPage) {
	echo '<body class="frontpage">';
} else {
	echo '<body>';
}
?>
	<div id="headerContainer">
		<div id='search-results' class="page-section">
			<span class="btn close-search-results">Close search results</span>
			<div id="cse"></div>
			<span class="btn close-search-results">Close search results</span>
		</div>
		<div id="header">

<?php
if(true || $isFrontPage) {
?>
		<div class="header-image">
			<div id="header-text-container">
				<div id="header-back"></div>
				<h1>Hi! I'm Erik.</h1>
				<h2>I blog mostly about photography, technology and related topics: DIY, techniques, and product reviews.</h2>
			</div>
		</div>
<?php
}
?>
		</div>
	</div>
	<div id="menucontainer">
		<a href="/" id="logo"></a>
		<a id="menu-toggle" href="javascript:void(0);" title="Toggle menu"><i class="fa fa-bars"></i></a>
		<div id="open-search"><a href="javascript:void(0);"><i class="fa fa-search"></i></a></div>
		<div id='cse-search-form'>
			<form class="gsc-search-box" accept-charset="utf-8" id="cse-search-form-inner">
				<table cellspacing="0" cellpadding="0" class="gsc-search-box">
					<tbody>
						<tr>
							<td class="gsc-input">
								<input autocomplete="off" type="text" id="gsc-search-input" size="10" class="gsc-input" name="search" title="search" style="outline: none; background: rgb(255, 255, 255); text-indent: 0px;">
							</td>
							<td class="gsc-search-button">
								<input type="submit" value="Search" class="gsc-search-button" title="search">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>

		<ul id="horizontal">
<?php
$namesArr = array('Home','Images','Downloads','Icon&nbsp;Maker','Contact');
$urlArr = array('','images','download','iconmaker','contact');
for($i=0;$i<count($namesArr);$i++)
{
	echo "<li" . ($highlightitem == $i ? " class=\"active\"" : "") . "><a href=\"/$urlArr[$i]\">$namesArr[$i]</a></li> ";
}
?>
		</ul>
	</div>
	<div id="containercontainer">
	<div id="container">
	<div id="content">
<?php
}

function PrintEndHtml($isFrontPage = false, $myShareTitle = null, $myShareUrl = null)
{
	$isFrontPage = false
?>
</div>
<?php
if(!$isFrontPage) {
	echo '</div>';
}
?>
	<aside>
		<div id="sideInfo">
			<div id="main-sideinfo">
				<div class="page-section">
					<h2 class="firstHeader">About me</h2>
					<img src="/content/images/me.jpg" id="self-image" alt="Portrait of Erik Moberg" />
					<p>As a programmer with just enough time on my hands, I occasionally blog about my hobbies - mostly photography and various gadgets.</p>
				</div>

				<div class="social-container page-section">
					<h2>Share</h2>
					<?php
						$shareTitle = "erikmoberg.net";
						if($myShareTitle != null) {
							$shareTitle = $myShareTitle;
						}

						PrintSocialNetworkingLinks($shareTitle, $myShareUrl);
					?>

				</div>

				<div class="page-section">
					<h2>Follow</h2>
					<a target="_blank" class="rss-link" href="/rss.xml" title="Subscribe by RSS"><i class="fa fa-rss"></i> RSS</a>
					<a target="_blank" class="twitter-link" href="https://twitter.com/erikmoberg_swe" title="Follow me on Twitter"><i class="fa fa-twitter"></i> Twitter</a>
				</div>

			</div>
			<div id="additional-sideinfo">
				<div class="page-section">
					<h2 id="flickr-header">flickr uploads</h2>
					<div id="flickr-recent">
						<p>Loading images...</p>
					</div>
				</div>

	<div class="page-section">
	<h2>Recent Comments</h2>
	<?php PrintRecentComments(5); ?>

	</div>
	<div class="page-section">
	<h2>Most Commented Articles</h2>
	<?php PrintMostCommentedArticlesList(); ?>
	</div>
	<?php
				//<div class="page-section">
					//<h2>Blogroll</h2>
					//<p><a href="http://itochpedagog.wordpress.com/" target="_blank">IT och Pedagog (Swedish)</a> - podcast about using modern technology in education.</p>
					//<p><a href="http://www.snapp.de/" target="_blank">Robert's blog (German)</a> - also a friend of mine, mostly blogging about travelling and online marketing.</p>
				//</div>
			//</div>
			//<div class="clearfix"></div>
			?>
		</div>
		<div class="clearfix"></div>
	</aside>
<?php
if($isFrontPage) {
	echo '</div>';
}

?>
<div class="clearfix"></div>
	</div>

<div id="bottom">
</div>
<div id="last">
<a href="/about" rel="author">Erik Moberg</a>&nbsp;
<?php
echo date("Y");
?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>
<script src="/include/scripts.js.php?_=6" type="text/javascript"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-1716455-2', 'auto');
  ga('send', 'pageview');
</script>
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-min.js" type="text/javascript"></script>
<script src="/include/scripts.js.php?iconmaker=1&amp;_=6" type="text/javascript"></script>
</body>
</html>
<?php
}

?>
