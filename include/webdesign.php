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
	$isFrontPage = false;
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
	<link rel="stylesheet" type="text/css" href="/content/styles/style.css.php?_=18" />
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
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
	<meta name="description" content="<?php echo $metadescription; ?>" />
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
  <script>
		var pageTheme = sessionStorage.getItem('theme');
		if (pageTheme) {
			if (pageTheme === 'dark') {
					$("body").addClass("dark-theme");
			}
		} else {
			var currentHours = new Date().getHours();
			if (currentHours >= 19 || currentHours <= 5) {
				$("body").addClass("dark-theme");
			}
		}
  </script>
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


        <div class="change-theme-container">
    <a href="javascript:void(0);" id="changetheme-dark"><i class="fa fa-moon-o"></i></a>/<a href="javascript:void(0);" id="changetheme-light"><i class="fa fa-sun-o"></i></a>
</div>

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

function PrintEndHtml($isFrontPage = false, $myShareTitle = null, $myShareUrl = null, $pageHint = null)
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

                <div class="page-section">
                    <h2 id="flickr-header">flickr uploads</h2>
                    <div id="flickr-recent">
                        <p>Loading images...</p>
                    </div>
                </div>

			</div>
			<div id="additional-sideinfo">

                <div class="page-section">
                    <h2>Recent Comments</h2>
                    <?php PrintRecentComments(5); ?>
                </div>

                <div class="page-section">
                    <h2>Most Commented Articles</h2>
                    <?php PrintMostCommentedArticlesList(); ?>
                </div>

                <div class="page-section">
                    <h2>Ten Tiny Levels</h2>
                    <p>
                        My 100% free game for Android - fast-paced platform arcade action for one or two players! Check out the <a href="/ten-tiny-levels">microsite</a> or download using the badge below.
                    </p>
                    <div style="text-align: center; margin-top: -5px;">
                        <a href="https://play.google.com/store/apps/details?id=com.regalraccoongames.tentinylevels">
                            <img class="google-play-badge" src="/ttl-assets/google-play-badge.png" style="width: 200px; max-width: 100%;" />
                        </a>
                    </div>
				</div>
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
Erik Moberg&nbsp;
<?php
echo date("Y");
?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>
<script src="/include/scripts.js.php?_=10" type="text/javascript"></script>
<?php
if ($pageHint == "shiny-iconmaker") {
	?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-min.js" type="text/javascript"></script>
	<script src="/include/scripts.js.php?iconmaker=1&amp;_=8" type="text/javascript"></script>
	<?php
}
?>
</body>
</html>
<?php
}

?>
