<?php
include_once("include/webdesign.php");
PrintStartHtml('Contact',4,'Use the contact form to send me a message.');
?>

<?php
$displayform = true;
if(isset($_GET['message']))
{
	if($_GET['message'] == "posted")
	{
		echo '<div class="page-section">';
		echo '<h2>Thank you! </h2><h3>Message was sent.</h3>';
		echo '</div>';
		$displayform = false;
	}
}
?>

<?php
if($displayform)
{
?>
<div class="page-section">
	<h2>About</h2>
	<p>
	Although great website building tools exist, being a web developer but not having a hand-crafted web site felt just plain wrong.
	I have been working as a software developer since 2005, specializing in web development using ASP.NET - MVC as well as Core - with C# along with some client side magic using JavaScript, with libaries and frameworks such as React and Angular.
	So as a side project, I set up this web site to make use of my skills and learn a few new ones.
	</p>
	<p>
	The site uses PHP, and is programmed with Atom. For image manipulation, GIMP and Inkscape are used.
	The image gallery and the first page use XML for storing data, handled by a simple CMS-like application.
	</p>
	<p>
	I also like photography and travelling. You can find some of my pictures in the <a href="/images">image gallery</a>.
	</p>
	<div class="clearfix"></div>
</div>
<div class="page-section">

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
		$to = "erik@erikmoberg.net";
		$subject = "erikmoberg.net contact - $name";
		$message = "$email - $message";
		$header = "From: erik@erikmoberg.net";

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
<?php
}
echo '</div>';
PrintEndHtml();
?>
