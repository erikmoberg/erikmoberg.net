<?php

function do_post_request($url, $postdata, $svgdata, $files = null)
{
    $data = "";
    $boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);
      
    //Collect Postdata
    foreach($postdata as $key => $val)
    {
        $data .= "--$boundary\r\n";
        $data .= "Content-Disposition: form-data; name=\"".$key."\"\r\n\r\n".$val."\r\n";
    }
    
    $data .= "--$boundary\n";
   
	$fileContents = $svgdata;
   
	$data .= "Content-Disposition: form-data; name=\"stdin\"; filename=\"icon.svg\"\n";
	$data .= "Content-Type: image/svg+xml\n";
	$data .= "\r\n";
	$data .= $fileContents."\r\n";
	$data .= "--$boundary--\n";
 
    $params = array('http' => array(
           'method' => 'POST',
           'header' => 
		   "Cache-Control: no-cache\r\n".
                "Pragma: no-cache\r\n".
						'Content-Type: multipart/form-data; boundary='.$boundary,
           'content' => $data
        ));

		$response = null;
		$lastlength = -1;
		$maxlength = -1;
		
		for($i=0;$i<10;$i++)
		{
		   $ctx = stream_context_create($params);
		   $fp = fopen($url, 'r', false, $ctx);
		 
		   $response = stream_get_contents($fp,-1);

		   fclose($fp);
		   
		   if($lastlength >= $maxlength && $lastlength == strlen($response))
		   {
			return $response;
		   }
		   
		   $lastlength = strlen($response);
		   if($lastlength > $maxlength)
				$maxlength = $lastlength;
		}
   
   return $response;
}

$pngsize = $_POST['pngsize'];
$svgdata = $_POST['stdin'];

$postdata = array();
if($pngsize != null && $pngsize != '') {
	$postdata = array(
		'width' => $pngsize,
		'height' => $pngsize,
	);
}

$regExpStr = '/xmlns="http:\/\/www.w3.org\/2000\/svg"/';
$noOfMatches = preg_match_all($regExpStr,$svgdata, $matches);
if($noOfMatches > 1) {
	$svgdata = preg_replace($regExpStr, '', $svgdata, 1);
}

$response = do_post_request("http://www.fileformat.info/convert/image/svg2png.htm", $postdata, $svgdata);
/*
$FileName = 'testicon.png';
$subhandle = fopen($FileName, "a+");
fwrite($subhandle,$response);
fclose($subhandle);
*/

header("Content-type: image/png");
header("Content-Disposition: attachment; filename=\"icon.png\"");
print $response;


?>