<?php
if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
    ini_set("zlib.output_compression", 1);
}
header ('content-type: text/css; charset: UTF-8');
header ('cache-control: must-revalidate');
$offset = 60 * 60 * 24 * 7;
$expire = 'expires: ' . gmdate ('D, d M Y H:i:s', time() + $offset) . ' GMT';
header ($expire);

readfile('style.css');
//readfile('../../include/highslide/highslide.css');
readfile('../../include/spectrum/spectrum.css');
?>