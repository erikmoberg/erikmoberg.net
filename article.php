<?php
//ini_set('display_errors', '1');
include_once("include/webdesign.php");
include_once("include/bloghandler.php");

$readableid = '';
if(isset($_GET['name']))
	$readableid = $_GET['name'];

if($readableid == '')
{
	header("Location: /");
	return;
}
	
$header = GetArticleHeader($readableid);

if($header == '')
{
	header("Location: /");
	return;
}

$metadescription = GetArticleIntro($readableid);

PrintStartHtml($header,-1,$metadescription);

PrintNextAndPreviousArticleLinks($readableid, false);

PrintArticle($readableid);

?>
<div class="social-container page-section">
<?php
PrintSocialNetworkingLinks(GetArticleSubHeader($readableid) . ': ' . $header);
?>
</div>
<div class="comments">
<a name="comments"></a>

<?php
$numberOfComments = GetNoOfCommentsForArticle($readableid);

echo '<div class="page-section">';
echo "<h2><span id='comment-counter'>$numberOfComments</span> <span id='comment-text'>Comment" . ($numberOfComments != 1 ? 's' : '') . "</span></h2>";
echo '<a target="_blank" class="rss-link" href="/rss/' . $readableid . '.xml"><i class="fa fa-rss"></i> Subscribe to new comments by RSS</a>';
echo '</div>';
echo '<div id="comment-entries">';

PrintComments($readableid);	
echo '</div>';
echo '<div style="clear: both;"></div>';
echo '</div>';
?>

<div id="frmComments">

<h2 class="commentformheader">Leave a comment</h2>

<noscript>
	<h3>JavaScript has to be turned ON. You have JavaScript currently turned off; you will not be able to post a message.</h3>
</noscript>

<p>
<label for="txtName">Name</label>
<label id="txtNameError" class="form-error"></label>
<br />
<input type="text" class="textinput" name="txtName" id="txtName" />
</p>

<p>
<label for="txtWebsite">Web site (optional)</label>
<label id="txtWebsiteError" class="form-error"></label>
<br />
<input type="text" class="textinput" name="txtWebsite" id="txtWebsite" />
</p>

<p>
<label for="txtMessage">Comment</label>
<label id="txtMessageError" class="form-error"></label>
<br />
<textarea class="textinput" name="txtMessage" id="txtMessage" onkeydown="return checkMaxLength(this,event,1000)" onkeyup="return checkMaxLength(this,event,1000)" onfocus="return checkMaxLength(this,event,1000)" onblur="return checkMaxLength(this,event,1000)"></textarea>
<textarea rows="5" cols="54" class="textinput" name="someTxt" id="someTxt"></textarea>
<br />
<br />
<button class="btn" id="btnSend">Post comment</button>
<label id="btnSendError" class="form-error"></label>
</p>

<div id="comments-shroud" class="shroud"></div>

<div id="submitting-form">
	<h2>This will just take a second.</h2>
	<span>Submitting your comment...<span>
</div>

</div>

<?php
echo '<div class="page-section">';
echo '<a target="_blank" class="rss-link" href="/rss/' . $readableid . '.xml"><i class="fa fa-rss"></i> Subscribe to new comments by RSS</a>';
echo '</div>';
?>

<script type="text/javascript">

$(function() {

	$('#btnSend').on('click', function() {
		$('.form-error').hide();
		
		var formData = {
			name: $('#txtName').val(), 
			message: $('#txtMessage').val(), 
			website: $('#txtWebsite').val(),
			readableid: '<?php echo $readableid; ?>'
		};
		
		var formErrors = validateCommentForm(formData);
		
		if (formErrors.length > 0) {
			$.each(formErrors, function(i, formError) {
				$('#' + formError.field + 'Error')
					.html(formError.message)
					.show()
					.css({backgroundColor: '#FF0000'})
					.animate({backgroundColor: 'transparent'}, '200');
			});
		}
		else {
			$('#comments-shroud, #submitting-form').fadeTo(200, 0.7);
			$.post('/savecomment.php', formData, function(result) {
				if (!result.success) {
					$('#comments-shroud, #submitting-form').hide();
					alert(result.reason);
				}
				else {
					$('#frmComments').remove();
					$('#comment-entries').append(result.markup);
					$('#comment-counter').html(parseInt($('#comment-counter').html())+1);
					$('#comment-text').html($('#comment-counter').html() == '1' ? 'Comment' : 'Comments');
					$('html, body').animate({ scrollTop: $('#' + result.id).offset().top - $('#menucontainer').height() - 10 }, 500, function() {
						setTimeout(function() {
							$('#' + result.id).css({backgroundColor: '#da4526'})
								.animate({backgroundColor: '#ffe9c3'}, 1500);
						}, 50);
					});
				}
			}, 'json');
		}
	});
});

</script>

<?php

PrintNextAndPreviousArticleLinks($readableid, true);

$shortAddress = GetArticleShortUrl($readableid);
$text = GetArticleSubHeader($readableid) . ' - ' . GetArticleHeader($readableid);

PrintEndHtml(false, $text, $shortAddress);
?>