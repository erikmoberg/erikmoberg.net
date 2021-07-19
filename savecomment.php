<?php
	include_once('include/commenthandler.php');

	if(!isset($_POST['name'])) {
		echo json_encode(array('success' => false, 'reason' => 'Name is required.'));
		return;
	}
	
	$name = $_POST['name'];
	
	if(!isset($_POST['message'])) {
		echo json_encode(array('success' => false, 'reason' => 'Comment is required.'));
		return;
	}
	
	// message may be max 1000 characters
	$message = $_POST['message'];
	if(strlen($message) > 1000)
	{
		echo json_encode(array('success' => false, 'reason' => 'Comment length may not exceed 1000 characters.'));
		return;
	}
	
	$website = isset($_POST['website']) ? $_POST['website'] : null;
	
	if(!isset($_POST['readableid'])) {
		echo json_encode(array('success' => false, 'reason' => 'General error occurred saving the comment. Please reload the page.'));
		return;
	}
	
	$readableid = $_POST['readableid'];
	
	$commentId = AddComment($readableid, $name, $website, $message);
	
	if ($website != null && $website != '') {
		$website = CreateValidUrl($website);
	}
	$datetime = date('F dS, Y H:m');
	
	$message = AddLinksToMessage($message);
	$markup = GetSingleCommentMarkup($name, $datetime, $website, $message, $commentId);
	echo json_encode(array('success' => true, 'id' => 'comment-' . $commentId, 'markup' => $markup));
    return;
?>
