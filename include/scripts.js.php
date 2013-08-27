<?php
if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
    ini_set("zlib.output_compression", 1);
}
header ('content-type: text/javascript; charset: UTF-8');
header ('cache-control: must-revalidate');
$offset = 60 * 60;
$expire = 'expires: ' . gmdate ('D, d M Y H:i:s', time() + $offset) . ' GMT';
header ($expire);

$iconmaker = false;
if(isset($_GET['iconmaker'])) {
	$iconmaker = true;
}

if ($iconmaker) {
	readfile('jscolor/jscolor.js');
	readfile('iconmaker.js');
	readfile('raphael-min.js');
	echo ';';
	readfile('raphael-icons.js');
	readfile('scale.raphael.js');
	readfile('rgbcolor.js');
	readfile('canvg.js');
	
}
else {
	readfile('json2.min.js');
	readfile('highslide/highslide-with-gallery.packed.js');
	readfile('scripts.js');
	//readfile('jquery.hammer.min.js');
}
?>