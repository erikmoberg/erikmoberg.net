<?php
//ini_set('display_errors', '1');
include_once("include/webdesign.php");
include_once("include/guestbookhandler.php");
PrintStartHtml('Guestbook',2,'Guestbook');
?>

<?php
$name = '';
$email = '';
$message = '';
if(isset($_POST['btnSend']))
{
	$name = $_POST['txtName'];
	$website = $_POST['txtWebsite'];
	$email = $_POST['txtEmail'];
	$message = $_POST['txtMessage'];
	$spamTest = $_POST['someTxt'];
	if($email == 'emalj')
		AddPost($name, $website, $email, $message, $spamTest);
}
$page = 1;
if(isset($_GET['page']))
	$page = $_GET['page'];
?>

<?php
$displayform = true;
if(isset($_GET['message']))
{
	if($_GET['message'] == "posted")
	{
		echo '<h2>Note: Message posted.</h2>';
		$displayform = false;
	}
}
if($displayform)
{
?>
<h2>Guestbook</h2>

<p>Feel free to post a message.</p>

<noscript><h3>JavaScript has to be turned ON. You have JavaScript currently turned off; you will not be able to post a message.</h3></noscript>
<form charset="UTF-8" id="frmGuestbook" method="post" action="guestbook.php<?php echo GetQueryString(); ?>">
<p>
<label for="txtName">Name</label><br />
<input type="text" class="textinput" name="txtName" id="txtName" />
</p>

<p>
<label for="txtWebsite">Web site (optional)</label><br />
<input type="text" class="textinput" name="txtWebsite" id="txtWebsite" />
</p>

<p>
<input type="hidden" name="txtEmail" id="txtEmail" value="none" />
</p>

<p>
<label for="txtMessage">Message</label><br />
<textarea rows="5" cols="54" class="textinput" name="txtMessage" id="txtMessage" onkeydown="checkMaxLength(this,1000)" onkeyup="checkMaxLength(this,1000)" onfocus="checkMaxLength(this,1000)" onblur="checkMaxLength(this,1000)"></textarea>
<textarea rows="5" cols="54" class="textinput" name="someTxt" id="someTxt"></textarea>
<br />
<input type="submit" class="btn" id="btnSend" name="btnSend" onclick="javascript:document.getElementById('txtEmail').value = 'emalj';return postMsg('txtName', 'txtWebsite', 'txtEmail', 'txtMessage');" value="Post message" />
</p>

</form>

<?php
}
PrintEntries($page);
?>
<script type="text/javascript">
document.getElementById('txtName').focus();
</script>
<?php
PrintEndHtml();
?>