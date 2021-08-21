<?php
	include_once('include/commenthandler.php');

    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data["name"];
    $message = $data["message"];
    $readableid = $data["readableid"];
    $someTxt = $data["someTxt"];

	if(empty($name)) {
		echo json_encode(array('success' => false, 'reason' => 'Name is required.'));
		return;
	}
	
	if(empty($message)) {
		echo json_encode(array('success' => false, 'reason' => 'Comment is required.'));
		return;
	}
	
	// message may be max 1000 characters
	if(strlen($message) > 1000)
	{
		echo json_encode(array('success' => false, 'reason' => 'Comment length may not exceed 1000 characters.'));
		return;
	}
	
    // website: not used
	$website = null;
	
	if(empty($readableid)) {
		echo json_encode(array('success' => false, 'reason' => 'General error occurred saving the comment. Please reload the page.'));
		return;
	}
	
    if (empty($someTxt)) {
        $commentId = AddComment($readableid, $name, $website, $message);
        
        if ($website != null && $website != '') {
            $website = CreateValidUrl($website);
        }
        $datetime = date('F dS, Y H:m');
        
        $message = AddLinksToMessage($message);
        $markup = GetSingleCommentMarkup($name, $datetime, $website, $message, $commentId);
        echo json_encode(array('success' => true, 'id' => 'comment-' . $commentId, 'markup' => $markup));
        return;
    } else {
        // $testText should be empty, if filled out, indicates spam. Fake success
        echo json_encode(array('success' => true, 'id' => 'comment-0', 'markup' => ''));
        return;
    }
?>
