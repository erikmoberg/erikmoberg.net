<?php
include_once("include/webdesign.php");
include_once("include/commenthandler.php");
PrintStartHtml('Approval',-1,'Approval');
?>

<?php

$articlename = $_GET['articlename'];
$commentdatetime = $_GET['commentdatetime'];
$newstatus = $_GET['newstatus'];
$key = $_GET['key'];

$errormessage = null;
if ($articlename == null) {
    $errormessage = 'Missing article name.';
}

if ($commentdatetime == null) {
    $errormessage = 'Missing date and time.';
}

if ($newstatus == null) {
    $errormessage = 'Missing new approval status.';
}

if($errormessage != null) {
    echo "<h2>$errormessage</h2>";
} else {
    $errormessage = SetApprovalStatus($articlename, $commentdatetime, $newstatus, $key);
    if($errormessage != null) {
        echo "<h2>$errormessage</h2>";
    } else {
        echo "<h2>Approval status updated to: $newstatus</h2>";
    }
}

PrintEndHtml();
?>
