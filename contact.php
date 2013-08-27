<?php
include_once("include/webdesign.php");
PrintStartHtml('Contact',5,'Use the contact form to send me a message.');
?>

<?php
$displayform = true;
if(isset($_GET['message']))
{
	if($_GET['message'] == "posted")
	{
		echo '<h2>Thank you! </h2><h3>Message was sent.</h3>';
		$displayform = false;
	}
}
?>

<?php
if($displayform)
{
?>
	<h2>Contact me</h2>
	<p>Use the form below to send me a message.</p>
<?php
}

$name = '';
$email = '';
$message = '';
$testtext = '';
if(isset($_POST['btnSend']))
{
	$name = htmlspecialchars($_POST['txtName']);
	$email = htmlspecialchars($_POST['txtEmail']);
	$message = htmlspecialchars($_POST['txtMessage']);
	$spamTest = htmlspecialchars($_POST['someTxt']);
	$testtext = htmlspecialchars($_POST['txtTesttext']);
	if($testtext == 'emalj' && $spamTest == '')
	{
		$to = "erikmoberg@hotmail.com";
		$subject = "erikmoberg.net contact - $name";
		$message = "$email - $message";
		$header = "From: erikmoberg";
		
		$header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
		mail($to, "=?UTF-8?B?".base64_encode($subject).'?=', $message, $header_ . $header);
		
		echo " <script>window.location=\"/contact/posted\"</script> ";
	}
}

if($displayform)
{
?>

<noscript><h3>JavaScript has to be turned ON. You have JavaScript currently turned off; you will not be able to post a message.</h3></noscript>
<form id="frmContact" method="post" action="contact">
<p>
<label for="txtName">Name</label><br />
<input class="textinput" type="text" style="width: 250px" name="txtName" id="txtName" />
</p>
<p>
<label for="txtEmail">Email</label><br />
<input class="textinput" type="text" style="width: 250px" name="txtEmail" id="txtEmail" />
<input type="hidden" name="txtTesttext" id="txtTesttext" value="none" />
</p>
<p>
<label for="txtMessage">Message</label><br />
<textarea class="textinput" rows="5" cols="54" name="txtMessage" id="txtMessage" onkeydown="checkMaxLength(this,1000)" onkeyup="checkMaxLength(this,1000)" onfocus="checkMaxLength(this,1000)" onblur="checkMaxLength(this,1000)"></textarea>
<textarea rows="5" cols="54" name="someTxt" id="someTxt"></textarea>
<br />
<input type="submit" id="btnSend" name="btnSend" class="btn" onclick="javascript:document.getElementById('txtTesttext').value = 'emalj';return postContact('txtName', 'txtEmail', 'txtTesttext', 'txtMessage');" value="Send message" />
</p>
</form>
<script type="text/javascript">
document.getElementById('txtName').focus();
</script>
<?php
}
PrintEndHtml();
?>