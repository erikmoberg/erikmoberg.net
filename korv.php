<?php
include_once("include/webdesign.php");
PrintStartHtml('Korv',3,'Korv');
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>

<div class="page-section">

<h2>Klicka här: <a href="http://orteil.dashnet.org/igm/?g=https://www.erikmoberg.net/idle/korv.txt?_=LiUgBC84ba">Till korvspelet</a></h2>

<?php
PrintEndHtml();
?>