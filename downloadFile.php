<?php

if(isset($_POST['svg']))
{
	$base64start = "data:image/png;base64,";
	$data = $_POST['svg'];
	$baseFileName = $_POST['downloadIconName'];

	$filename = $baseFileName . ".svg";
	$pos = strpos($data, $base64start);
	if($pos !== false) {
		$filename = $baseFileName . ".png";
		$data = str_replace($base64start,"",$data);
		$data = base64_decode($data);
	}

	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
	echo $data;
}
?>
