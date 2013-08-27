<?php
//ini_set('display_errors', '1');
include_once("include/webdesign.php");
include_once("include/bloghandler.php");
PrintStartHtml('Lol, admin',-1);
?>

<?php

if(!isset($_SESSION['adminok']))
{
	if(isset($_GET['test']))
	{
		if(isset($_POST['lol']))
		{
			if(md5($_POST['lol']) == "21232f297a57a5a743894a0e4a801fc3" && md5($_POST['hej']) == "d2b8c48915d8070bdd1664dc0e49d2df")
			{
				$_SESSION['adminok'] = "1";
				header("Location: blogadmin.php");
			}
		}
		?>
		<form method="post" action="blogadmin.php?test=1">
		<input type="password" id="lol" name="lol" />
		<input type="password" id="hej" name="hej" />
		<input type="submit" />
		</form>
	<?php
	}
	else
		header("Location: index.php");
}
else
{
	if(isset($_POST['btnSend']))
	{
		$header = htmlentities($_POST['txtHeader']);
		$body = htmlentities($_POST['txtBody']);
		AddEntry($header, $body);
	}
	?>

	<form method="post" action="blogadmin.php<?php echo GetQueryString(); ?>">
	<p>
	<label for="txtHeader">Header</label><br />
	<input type="text" name="txtHeader" id="txtHeader" />
	</p>
	<p>
	<label for="txtBody">Body</label><br />
	<textarea rows="15" cols="54" wrap="physical" name="txtBody" id="txtBody"></textarea>
	<button type="button" onclick="javascript:Preview();">Preview</button>
	<input type="submit" id="btnSend" name="btnSend" onclick="javascript:return postMsg('txtName', 'txtEmail', 'txtMessage');" value="Post message" />
	</p>
	</form>
	<div id="preview"></div>
	<script type="text/javascript">
	document.getElementById('txtHeader').focus();
	function Preview()
	{
		var header = document.getElementById('txtHeader').value;
		var body = document.getElementById('txtBody').value;
		document.getElementById('preview').innerHTML = '<h2>' + header + '</h2><p>' + body + '</p>';
	}
	</script>
	<?php
}
PrintEndHtml();
?>