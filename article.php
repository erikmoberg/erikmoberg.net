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
echo '<a target="_blank" class="rss-link" href="/rss/' . $readableid . '.xml">' . GetIconMarkup('rss') . ' Subscribe to new comments by RSS</a>';
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
<input type="text" class="textinput" id="txtName" />
</p>
<?php
//<p>
//<label for="txtWebsite">Web site (optional)</label>
//<label id="txtWebsiteError" class="form-error"></label>
//<br />
//<input type="text" class="textinput" name="txtWebsite" id="txtWebsite" />
//</p>
?>
<p>
<label for="txtMessage">Comment</label>
<label id="txtMessageError" class="form-error"></label>
<br />
<textarea id="someTxt"></textarea>
<textarea class="textinput" id="txtMessage" onkeydown="return checkMaxLength(this,event,1000)" onkeyup="return checkMaxLength(this,event,1000)" onfocus="return checkMaxLength(this,event,1000)" onblur="return checkMaxLength(this,event,1000)"></textarea>
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
echo '<a target="_blank" class="rss-link" href="/rss/' . $readableid . '.xml">' . GetIconMarkup('rss') . ' Subscribe to new comments by RSS</a>';
echo '</div>';
?>

<script type="text/javascript">

document.querySelector('#btnSend').addEventListener('click', function() {
    for (const errorLabel of document.querySelectorAll('.form-error')) {
        errorLabel.innerHTML = '';
        errorLabel.classList.remove('form-error-visible');
    }

    var formData = {
        name: document.querySelector('#txtName').value,
        message: document.querySelector('#txtMessage').value,
        readableid: '<?php echo $readableid; ?>',
        someTxt: document.querySelector('#someTxt').value
    };

    var formErrors = validateCommentForm(formData);

    if (formErrors.length > 0) {
        for (const formError of formErrors) {
            const errorLabel = document.querySelector('#' + formError.field + 'Error')
            errorLabel.innerHTML = formError.message;
            errorLabel.classList.add('form-error-visible');
        }
    }
    else {
        document.querySelector('#comments-shroud').style.display = 'block';
        document.querySelector('#submitting-form').style.display = 'block';

        fetch('/savecomment.php', 
        {
            method: 'POST',
            body: JSON.stringify(formData)
        }).then(function (response) {
            document.querySelector('#comments-shroud').style.display = 'none';
            document.querySelector('#submitting-form').style.display = 'none';
            if (!response.ok) {
                alert("error saving comment.");
                return;
            }

            response.json().then(function(result) {
                if (!result.success) {    
                    alert(result.reason);
                }
                else {
                    var commentForm = document.querySelector('#frmComments');
                    commentForm.parentNode.removeChild(commentForm);

                    var parent = document.querySelector('#comment-entries');
                    var newNode = document.createElement('div');
                    newNode.innerHTML = result.markup;
                    parent.appendChild(newNode);

                    const numberOfComments = parseInt(document.querySelector('#comment-counter').innerHTML) + 1;
                    document.querySelector('#comment-counter').innerHTML = numberOfComments;
                    document.querySelector('#comment-text').innerHTML = numberOfComments == 1 ? 'Comment' : 'Comments';
                    document.querySelector('#' + result.id).scrollIntoView({behavior: "smooth", block: "center"});
                    document.querySelector('#' + result.id).classList.add('pulse');
                }
            });
        });
    }
});
</script>

<?php

PrintNextAndPreviousArticleLinks($readableid, true);

$shortAddress = GetArticleShortUrl($readableid);
$text = GetArticleSubHeader($readableid) . ' - ' . GetArticleHeader($readableid);

PrintEndHtml(false, $text, $shortAddress);
?>
