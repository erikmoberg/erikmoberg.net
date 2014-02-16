<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
date_default_timezone_set('Europe/Berlin');
$pageFileName = '';
$metadescription = "Erik Moberg's personal homepage - " . ($metadescription == null ? "photography, travelling, web design and more" : $metadescription);

print <<<END
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Erik Moberg's personal homepage - photography, gadgets, DIY, and more - $pageTitle</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300|Raleway:200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="/styles/single/style.css.php?_=24" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
	<link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon" />
    <link rel="icon" href="/favicon.ico" type="image/vnd.microsoft.icon" />
	<link rel="alternate" type="application/rss+xml" href="/rss.php" title="erikmoberg.net" />
	<meta name="robots" content="index, follow" />
	<meta name="googlebot" content="index, follow" />
	<meta name="description" content="$metadescription" />
	<meta name="keywords" content="Erik Moberg, photography, cameras, travel, web design, web development, programming, personal blog, php, css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
	<div id="headerContainer">
		<div id="header">
			<h1>A blog mostly about photography and related stuff: DIY, techniques, and product reviews. Make sure to check out the image gallery.</h1>
			<a href="/">
				<img src="/styles/single/headerback.png" alt="header" />
			</a>
		</div>
		<div id='cse-search-form'>
			<span>Loading search...</span>
		</div>
	</div>	
	<div id="menucontainer">
		<div id="activeItemMarkerContainer">
			<div id="activeItemMarker"></div>
		</div>
		<ul id="horizontal">
END;
$namesArr = array('Home','Images','Downloads','Icon&nbsp;Maker','About','Contact');
$urlArr = array('','images','download','iconmaker','about','contact');
for($i=0;$i<count($namesArr);$i++)
{
	echo "<li" . ($highlightitem == $i ? " class=\"active\"" : "") . "><a href=\"/$urlArr[$i]\">$namesArr[$i]</a></li> ";				
}
print <<<END
		</ul>
	</div>
	<div id="containercontainer">
	<div id="container">	
		<div id="innercontainer">
			<div id='search-results'>
				<span class="btn close-search-results">Close search results</span>
			
				<div id="cse"></div>
				
				<span class="btn close-search-results">Close search results</span>
			</div>
END;
if($highlightitem == 0) 
{
print <<<END
<a target="_blank" class="rss-link mobile" href="/rss.xml"></a>
<a id="sideinfo-toggle" href="javascript:void(0);" title="Toggle info"></a>
	<aside>
		<div id="sideInfo">
			<div id="main-sideinfo">
				<h2 class="firstHeader">About me</h2>
				<img src="/content/me.jpg" id="self-image" alt="Erik Moberg" />
				<p>As a programmer with just enough time on my hands, I occasionally blog about my hobbies - mostly photography and various gadgets.</p>
				<div id="social-container">
				</div>
				<h2 id="flickr-header">flickr uploads</h2>
				<div id="flickr-recent">
					<p>Loading images...</p>
				</div>
			</div>
			<div id="additional-sideinfo">
END;

echo '<h2>Recent Comments</h2>';
PrintRecentComments(10);
echo '<h2>Most Commented Articles</h2>';
PrintMostCommentedArticlesList();
echo '<h2>Recent Articles</h2>';
PrintRecentBlogHeadings(40);
print <<<END
				<h2>Blogroll</h2>
				<p><a href="http://www.cinemassacre.com" target="_blank">Cinemassacre</a> - also known as The Angry Video Game Nerd. If you like old school consoles and Godzilla, this is the place.</p>
				<p><a href="http://henrikpettersson.blogg.se/" target="_blank">Henrik Petterson's blog (Swedish)</a> - a friend of mine, blogging about photography.</p>
				<p><a href="http://www.snapp.de/" target="_blank">Robert's blog (German)</a> - also a friend of mine, mostly blogging about travelling and online marketing.</p>
			</div>
		</div>
	</aside>
<div id="content" class="frontpage">
END;
}
else
	echo '<div id="content">';
}

function PrintEndHtml()
{
print <<<END
</div>
<div class="clearfix"></div>
		<div id="footer">
		</div>
	</div>
	</div>
	</div>
<div id="bottom">
</div>
<div id="last">
Copyleft <a href="/about" rel="author">Erik Moberg</a>&nbsp;
END;
echo date("Y");
print <<<END
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>
<script src="/include/scripts.js.php?_=1" type="text/javascript"></script>

<script src='//www.google.com/jsapi' type='text/javascript'></script>
<script type='text/javascript'>
google.load('search', '1', {language: 'en' });
google.setOnLoadCallback(function() {
  var customSearchOptions = {};
  var orderByOptions = {};
  orderByOptions['keys'] = [{label: 'Relevance', key: ''} , {label: 'Date', key: 'date'}];
  customSearchOptions['enableOrderBy'] = true;
  customSearchOptions['orderByOptions'] = orderByOptions;
  var customSearchControl = new google.search.CustomSearchControl('009823758688195186637:ho3hw4ugdlg', customSearchOptions);
  customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
  customSearchControl.setLinkTarget(google.search.Search.LINK_TARGET_SELF);
  customSearchControl.setSearchCompleteCallback(null,function() {
	if ($('#search-results:visible').length == 0) {
		$('#search-results').fadeIn({queue:false}).show("slide", { direction: "up" });
	}
  });
  var options = new google.search.DrawOptions();
  options.setSearchFormRoot('cse-search-form');
  options.setAutoComplete(true);
  customSearchControl.draw('cse', options);
}, true);
</script>

<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
 document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-1716455-2");
 pageTracker._trackPageview();
} catch(err) {}
</script>
<script src="http://ajax.aspnetcdn.com/ajax/knockout/knockout-2.2.0.js" type="text/javascript"></script>
<script src="/include/scripts.js.php?iconmaker=1&amp;_=8" type="text/javascript"></script>
</body>
</html>
END;
}

?>