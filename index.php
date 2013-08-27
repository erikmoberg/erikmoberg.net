<?php
//ini_set('display_errors', '1');
include_once("include/webdesign.php");
include_once("include/bloghandler.php");
include_once("include/commenthandler.php");

$page = 1;
if(isset($_GET['page'])) {
	$page = $_GET['page'];
}

$continuation = false;
if(isset($_GET['continuation'])) {
	$continuation = true;
}

if(!$continuation) {
	PrintStartHtml('Home',0,null);
}

$lastPage = PrintBlogEntries($page);

if (!$continuation) {
	if (!$lastPage) {
		PrintPageList($page);
	}
	
	?>
	<script type="text/javascript">
	var isLoadingEntries = false;
	var loadMoreEntries = function () {
		if (!isLoadingEntries) {
			var loadEntriesButton = $('#load-more-entries');
			isLoadingEntries = true;
			var oldText = loadEntriesButton.html();
			loadEntriesButton.html('Loading entries...');
			
			var pulse = function () {
				loadEntriesButton.delay(200).addClass('loading', 'slow').delay(50).removeClass('loading', 'slow', function() {
					if (isLoadingEntries) {
						pulse();
					}
				});
			};
			pulse();
			
			var pageNumber = parseInt(loadEntriesButton.data('pagenumber'));
			var url = '/index.php?page=' + pageNumber + '&continuation=true';
			$.get(url, function(data) {
				var content = $(data).hide();
				$('article').last().after(content);
				content.slideDown('fast', function() {
					isLoadingEntries = false;
				});
				loadEntriesButton.html(oldText);
				loadEntriesButton.data('pagenumber', pageNumber+1);
				if(content.filter('.no-more-entries').length) {
					loadEntriesButton.remove();
				}
			});
		}
	};
	
	$(window).scroll(function() {
		if ($('#load-more-entries').length && $(window).scrollTop() + $(window).height() > $(document).height() - ($('#bottom').height()+100)) {
			loadMoreEntries()
		}
	});

	$('#load-more-entries').on('click', function(e) {
		e.preventDefault();
		loadMoreEntries();
	});
	
	if (screen.width > 400) {
		$.get('/flickr.php?type=recent', function(data) {
			var photos = JSON.parse(data);
			var html = '';
			$.each(photos, function(i, photo) {
				html += '<a href="' + photo.url + '" target="_blank"><img onload="javascript:$(this).fadeIn(\'slow\');" src="' + photo.image + '" alt="recent" /></a>';
			});
			$('#flickr-recent').html(html);
		});
	}
	else {
		$('#flickr-recent').remove();
		$('#flickr-header').remove();
	}
	
	$('#sideinfo-toggle').on('click', function() {
			$(this).toggleClass('open');
			$('#main-sideinfo').toggleClass('open');
		});
	
	$('#social-container').html(
			'<h2>Follow</h2>' +
			'<a target="_blank" class="rss-link" href="/rss.xml" title="Subscribe by RSS"></a>' +
			'<a target="_blank" class="twitter-link" href="https://twitter.com/erikmoberg_swe" title="Follow me on Twitter"></a>' +
			'<h2>Share</h2>' +
			'<fb:like href="http://www.erikmoberg.net" layout="button_count" show_faces="false" width="100" font=""></fb:like>' +
			'<div style="margin-top: 8px;"></div>' +
			'<a style="margin-left: 10px;" href="http://twitter.com/share" class="twitter-share-button" data-text="Erik Moberg\'s personal web page" data-count="horizontal" data-via="erikmoberg_swe">Tweet</a>' +
			'<div style="margin-top: 5px;"></div>' +
			'<g:plusone></g:plusone><br />'
		);
			
	</script>
	
	<?php
	
	PrintEndHtml();
	
} else if ($lastPage) {
	echo '<input type="hidden" class="no-more-entries" />';
}
?>