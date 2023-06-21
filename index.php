<?php
ini_set('display_errors', '1');
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
			var loadEntriesButton = document.querySelector('#load-more-entries');
			isLoadingEntries = true;
			var oldText = loadEntriesButton.innerHTML;
			loadEntriesButton.innerHTML = 'Loading entries...';
            loadEntriesButton.classList.add('loading');
			
			var pageNumber = parseInt(loadEntriesButton.getAttribute('data-pagenumber'));
			var url = '/index.php?page=' + pageNumber + '&continuation=true';
			fetch(url)
              .then(function(response) { return response.text(); })
              .then(function(data) {
                loadEntriesButton.classList.remove('loading');
				var content = data;
				let articles = document.querySelectorAll('article');
                let article = articles[articles.length - 1];
                var newNode = document.createElement('div');
                newNode.innerHTML = content;
                article.parentNode.insertBefore(newNode, article.nextSibling);

                isLoadingEntries = false;
				loadEntriesButton.innerHTML = oldText;
				loadEntriesButton.setAttribute('data-pagenumber', pageNumber+1);
				if (content.indexOf('no-more-entries') > -1) {
				 	loadEntriesButton.style.display = 'none';
				}
			});
		}
	};

	document.querySelector('#load-more-entries').addEventListener('click', function(e) {
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