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

	$('#load-more-entries').on('click', function(e) {
		e.preventDefault();
		loadMoreEntries();
	});
		
	</script>
	
	<?php
	
	PrintEndHtml(true);
	
} else if ($lastPage) {
	echo '<input type="hidden" class="no-more-entries" />';
}
?>